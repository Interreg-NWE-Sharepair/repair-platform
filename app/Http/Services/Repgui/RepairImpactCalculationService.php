<?php

namespace App\Http\Services\Repgui;

use App\Imports\RepairImpactImport;
use App\Models\DeviceType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class RepairImpactCalculationService
{
    const RECOMMENDATION_REPAIR = 'repair';

    const RECOMMENDATION_BUY = 'buy_new';

    const COLUMN_PRODUCT_CATEGORY = 'product_name';

    const COLUMN_ENERGY_LABEL = 'energylabel';

    const COLUMN_EXPECTED_LIFETIME = 'expected_lifetime';

    const COLUMN_SCENARIO = 'scenario';

    const COLUMN_YEARS_AFTER_REPAIR = 'years_after_repair';

    const COLUMN_PRODUCTION_IMPACT = 'production_impact';

    const COLUMN_EOL_IMPACT = 'end_of_life_impact';

    const COLUMN_REPAIR_IMPACT = 'repair_impact';

    const COLUMN_USE_PHASE_IMPACT = 'use_phase_impact';

    const CATEGORY_IMPACT_TOOL = 'category_impact_tool';

    public function getExpectedLifetime(Collection $productImpactData): int
    {
        return $productImpactData->first()[self::COLUMN_EXPECTED_LIFETIME];
    }

    public function getExpectedRemainingLifetime(int $age, int $expectedLifetime): int
    {
        if ($age < 3 / 4 * $expectedLifetime) {
            return $expectedLifetime - $age;
        } else {
            return round(1 / 4 * $expectedLifetime);
        }
    }

    /**
     * @param \Illuminate\Support\Collection $productImpactData
     * @return int
     *
     * We calculate the total emission for the 'buy new' and 'repair' scenario for each amount of years after repair/replace
     * If the emission for the 'repair' scenario is smaller than the 'buy new' scenario, we know that that number of years is enough to compensate the repair emission
     *
     * This method returns null if the recommended situation is 'buy new'
     */
    public function getMinimumRequiredLifetimeForRepair(Collection $productImpactData): int
    {
        $totalsByYearsAfterRepair = $productImpactData->groupBy(self::COLUMN_YEARS_AFTER_REPAIR)->map(function ($data) {
                $data = $data->keyBy(self::COLUMN_SCENARIO);

                return [
                    'total_co2_buy' => $data[self::RECOMMENDATION_BUY][self::COLUMN_PRODUCTION_IMPACT] + $data[self::RECOMMENDATION_BUY][self::COLUMN_EOL_IMPACT] + $data[self::RECOMMENDATION_BUY][self::COLUMN_REPAIR_IMPACT] + $data[self::RECOMMENDATION_BUY][self::COLUMN_USE_PHASE_IMPACT],
                    'total_co2_repair' => $data[self::RECOMMENDATION_REPAIR][self::COLUMN_PRODUCTION_IMPACT] + $data[self::RECOMMENDATION_REPAIR][self::COLUMN_EOL_IMPACT] + $data[self::RECOMMENDATION_REPAIR][self::COLUMN_REPAIR_IMPACT] + $data[self::RECOMMENDATION_REPAIR][self::COLUMN_USE_PHASE_IMPACT],
                ];
            });

        return $totalsByYearsAfterRepair->filter(
            function ($data) { return $data['total_co2_repair'] <= $data['total_co2_buy']; }
        )->keys()->first();
    }

    public function getRepairScenarioImpact(Collection $productImpactData, int $expectedRemainingLifetime)
    {
        return $productImpactData->where(self::COLUMN_SCENARIO, '=', self::RECOMMENDATION_REPAIR)->where(
                self::COLUMN_YEARS_AFTER_REPAIR,
                '=',
                $expectedRemainingLifetime
            )->first();
    }

    public function getBuyScenarioImpact(Collection $productImpactData, int $expectedRemainingLifetime)
    {
        return $productImpactData->where(self::COLUMN_SCENARIO, '=', self::RECOMMENDATION_BUY)->where(
                self::COLUMN_YEARS_AFTER_REPAIR,
                '=',
                $expectedRemainingLifetime
            )->first();
    }

    public function getCalculationInfo(?int $productAge, string $productCategory)
    {
        $impactData = Excel::toCollection(new RepairImpactImport, 'repair_impact_tool_dataset.csv', 'repair-impact')
                           ->first();

        // filter on product, afterwards filter on first energylabel
        $productImpactData = $impactData->filter(
            function ($data) use ($productCategory) { return $data[self::COLUMN_PRODUCT_CATEGORY] == $productCategory; }
        );

        $defaultEnergyLabel = $productImpactData->first()[self::COLUMN_ENERGY_LABEL];
        $productImpactData = $productImpactData->filter(
            function ($data) use ($defaultEnergyLabel
            ) {
                return $data[self::COLUMN_ENERGY_LABEL] == $defaultEnergyLabel;
            }
        );

        $expectedLifetime = $this->getExpectedLifetime($productImpactData);
        $productAge = $productAge ?? $expectedLifetime;
        $expectedRemainingLifetime = $this->getExpectedRemainingLifetime($productAge, $expectedLifetime);
        $repairScenarioImpact = $this->getRepairScenarioImpact($productImpactData, $expectedRemainingLifetime);
        $buyScenarioImpact = $this->getBuyScenarioImpact($productImpactData, $expectedRemainingLifetime);

        $eolImpact = $buyScenarioImpact[self::COLUMN_EOL_IMPACT];
        $productionImpact = $buyScenarioImpact[self::COLUMN_PRODUCTION_IMPACT];
        $buyUsePhaseImpact = $buyScenarioImpact[self::COLUMN_USE_PHASE_IMPACT];
        $totalBuyImpact = $eolImpact + $productionImpact + $buyUsePhaseImpact;

        $repairImpact = $repairScenarioImpact[self::COLUMN_REPAIR_IMPACT];
        $repairUsePhaseImpact = $repairScenarioImpact[self::COLUMN_USE_PHASE_IMPACT];
        $totalRepairImpact = $repairImpact + $repairUsePhaseImpact;

        if ($totalRepairImpact <= $totalBuyImpact) {
            $scenario = self::RECOMMENDATION_REPAIR;
            $co2_savings = $totalBuyImpact - $totalRepairImpact;
        } else {
            $scenario = self::RECOMMENDATION_BUY;
            $co2_savings = $totalRepairImpact - $totalBuyImpact;
        }

        $equivalentForRepair = $this->getEquivalentForEmission($totalRepairImpact);
        $equivalentForBuy = $this->getEquivalentForEmission($totalBuyImpact);
        $equivalentForSavings = $this->getEquivalentForEmission($co2_savings);

        return [
            'category_key' => $productCategory,
            'category' => $this->getTranslatedProductCategory($productCategory),
            'age' => $productAge,
            'expectedLifetime' => $expectedLifetime,
            'expectedRemainingLifetime' => $expectedRemainingLifetime,
            'recommendation' => $scenario,
            'co2_repair' => round($totalRepairImpact),
            'co2_buy' => round($totalBuyImpact),
            'co2_savings' => round($co2_savings),
            'eq_repair_days' => $equivalentForRepair['days'],
            'eq_repair_hours' => $equivalentForRepair['hours'],
            'eq_buy_days' => $equivalentForBuy['days'],
            'eq_buy_hours' => $equivalentForBuy['hours'],
            'eq_savings_days' => $equivalentForSavings['days'],
            'eq_savings_hours' => $equivalentForSavings['hours'],
            'minRequiredLifetime' => $this->getMinimumRequiredLifetimeForRepair($productImpactData),
            'chart' => $this->getChartData(
                $eolImpact,
                $productionImpact,
                $buyUsePhaseImpact,
                $repairImpact,
                $repairUsePhaseImpact
            ),
        ];
    }

    /**
     * @param float $emission
     * @return array with the equivalent days and hours of for example charging your phone for the given emission.
     * Both the days and hours represent the full value and can be displayed independently. Its x hours or y days, not x hours and y days.
     */
    public function getEquivalentForEmission(float $emission)
    {
        $emission_per_equivalent_unit = 0.0052;
        $eq_hours = round($emission / $emission_per_equivalent_unit);
        if ($eq_hours < 24) {
            return ['days' => 0, 'hours' => $eq_hours];
        }

        $eq_days = round($eq_hours / 24);

        return ['days' => $eq_days, 'hours' => $eq_hours];
    }

    public function getTranslatedProductCategory(string $productCategory): string {
        return trans(
            'repgui.repair_impact_calculation_category_' . preg_replace('/[^a-zA-Z0-9]+/', '', $productCategory)
        );
    }

    public function getProductCategories()
    {
        $impactData = Excel::toCollection(new RepairImpactImport, 'repair_impact_tool_dataset.csv', 'repair-impact')
                           ->first();

        return $impactData->pluck(self::COLUMN_PRODUCT_CATEGORY)->unique()->values()->mapWithKeys(function ($item) {
                return [
                    $item => $this->getTranslatedProductCategory($item),
                ];
            });
    }

    public function getAge($age): int|null
    {
        return collect(['' => null, '2' => 2, '2-5' => 5, '5-10' => 10, '10-15' => 15, '15' => 15])->get($age);
    }

    public function getChartData(
        $eolImpact,
        $productionImpact,
        $buyUsePhaseImpact,
        $repairImpact,
        $repairUsePhaseImpact
    ) {
        $totalBuyImpact = $eolImpact + $productionImpact + $buyUsePhaseImpact;
        $totalRepairImpact = $repairImpact + $repairUsePhaseImpact;

        $maxImpact = max($totalBuyImpact, $totalRepairImpact);
        $numberOfTicks = 5;
        $stepSize = round($maxImpact / $numberOfTicks);
        $stepSizeNearest10 = round(($stepSize + 10 / 2) / 10) * 10;
        $maxY = round(($maxImpact + $stepSizeNearest10 / 2) / $stepSizeNearest10) * $stepSizeNearest10;

        return [
            'maxY' => $maxY,
            'step' => $stepSizeNearest10,
            'repair' => [
                'production' => 0,
                'eol' => 0,
                'repair' => round(($repairImpact / $maxY) * 100),
                'usage' => round(($repairImpact / $maxY) * 100) + round(($repairUsePhaseImpact / $maxY) * 100),
            ],
            'buy' => [
                'production' => round(($productionImpact / $maxY) * 100),
                'eol' => round(($productionImpact / $maxY) * 100) + round(($eolImpact / $maxY) * 100),
                'repair' => round(($productionImpact / $maxY) * 100) + round(($eolImpact / $maxY) * 100),
                'usage' => round(($productionImpact / $maxY) * 100) + round(($eolImpact / $maxY) * 100) + round(
                        ($buyUsePhaseImpact / $maxY) * 100
                    ),
            ],
        ];
    }

    /**
     * @param $categoryCode
     * @return null
     */
    public function getImpactForGuidance($categoryCode) :string|null
    {
        $impactData = Excel::toCollection(new RepairImpactImport, 'mapping-device-types-impact.csv', 'repair-impact')
                           ->first();
        $impactRow = collect($impactData)->filter(function ($item) use ($categoryCode) {
                return $item['code'] === $categoryCode && !is_null($item[self::CATEGORY_IMPACT_TOOL]);
            })->unique()->first();

        if ($impactRow) {
            return $impactRow[self::CATEGORY_IMPACT_TOOL];
        }

        return null;
    }

    public function getGuidanceForImpact($category): DeviceType|null {
        $impactData = Excel::toCollection(new RepairImpactImport, 'mapping-device-types-impact.csv', 'repair-impact')
                           ->first();

        $impactRow = collect($impactData)->filter(function ($item) use ($category) {
            return $item[self::CATEGORY_IMPACT_TOOL] === $category && !is_null($item['code']);
        })->unique()->first();

        if ($impactRow) {
            return DeviceType::whereCode($impactRow['code'])->first();
        }

        return null;
    }
}
