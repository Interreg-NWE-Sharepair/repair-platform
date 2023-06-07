<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\DeviceLogRequest;
use App\Http\Resources\V1\DeviceCollection;
use App\Models\Device;
use App\Models\Location;
use App\Models\RepairLog;
use App\Services\RepairDeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class DeviceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response|JsonResource
     *
     * @OA\Get(
     *  path="/devices",
     *  summary="Devices",
     *  description="Display a listing of devices",
     *  operationId="getDevicesList",
     *  tags={"Devices"},
     *  @OA\Parameter(in="query", name="per_page", example="10"),
     *  @OA\Parameter(in="query", name="page", example="1"),
     *  @OA\Parameter(in="query", name="limit", example="10"),
     *  @OA\Parameter(in="query", name="updated_after", example="2022-02-22T07:48:23Z", description="All devices updated after a certain date. Using ISO 8601, can be a date or a datetime"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/DeviceCollection")
     *        ),
     *  @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *  ),
     *  @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *  )
     * )
     */
    public function index(Request $request)
    {
        return new DeviceCollection(Device::with('deviceType.parent', 'organisation.locations', 'repairLog.repairBarriers')->where('latest_status', RepairLog::STATUS_COMPLETED)
            ->search($request)
            ->getByQueryParameters());
    }

    /**
     * Log a new device
     *
     * @OA\Post (
     *  path="/devices/log/completed",
     *  summary="Log a new completed repair device",
     *  description="Log a new completed repair device",
     *  operationId="logCompletedDevice",
     *  tags={"Devices"},
     *  @OA\RequestBody(
     *      required=true,
     *      description="Log a new completed device",
     *      @OA\JsonContent(ref="#/components/schemas/DeviceLog")
     *  ),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *        ),
     *  @OA\Response(
     *     response=422,
     *     description="Validation error",
     *  ),
     *  @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *  ),
     *  @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *  )
     * )
     *
     * @param \App\Http\Requests\Api\V1\DeviceLogRequest $request
     * @param \App\Services\RepairDeviceService $repairDeviceService
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function logCompletedDevice(DeviceLogRequest $request, RepairDeviceService $repairDeviceService)
    {
        $data = $request->validated();

        /** @var \App\Models\Organisation $organisation */
        $organisation = Location::where('uuid', $data['location'])->first()->organisation;
        if ($organisation->organisationType->code !== 'professional_repairer') {
            $error = ValidationException::withMessages([
                'location' => ['At the moment only professional repairers can log devices with the api.'],
            ]);

            throw $error;
        }

        $repairLog = $repairDeviceService->logNewCompletedDevice($data);

        return response()->json([
            'success' => true,
            'device_id' => $repairLog->device->uuid,
            'repair_log_id' => $repairLog->uuid,
        ]);
    }
}
