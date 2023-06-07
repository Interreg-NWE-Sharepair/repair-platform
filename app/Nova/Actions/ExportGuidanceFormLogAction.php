<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportGuidanceFormLogAction extends DownloadExcel
{

    use InteractsWithQueue, Queueable;


    public function map($log): array
    {
        /** @var \App\Models\RepairGuidanceFormLog $log */
        return [
            $log->id,
            $log->deviceType->name,
            $log->brand_name,
            $log->model_name,
            $log->product_description,
            $log->product_age,
            $log->common_issue_text,
            $log->commonDeviceTypeIssues()->pluck('issue')->implode(','),
            $log->created_at
        ];
    }

    public function headings(): array
    {

        return [
            'ID',
            'Device Type',
            'Brand name',
            'Model name',
            'Product description',
            'Product age',
            'Common issue text',
            'Common device type issues',
            'created_at'
        ];
    }

    public function name() {
        return 'Export Guidance Log entries';
    }
}
