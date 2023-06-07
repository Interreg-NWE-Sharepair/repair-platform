<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\CompletedRepairStatusCollection;
use App\Models\CompletedRepairStatus;
use App\Models\Device;
use Illuminate\Http\Request;

class CompletedRepairStatusController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\V1\CompletedRepairStatusCollection
     *
     * @OA\Get(
     *  path="/completed_repair_statuses",
     *  summary="Completed repair statuses",
     *  description="Display a listing of completed repair statuses",
     *  operationId="getCompletedRepairStatusesList",
     *  tags={"Completed repair statuses"},
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/CompletedRepairStatusCollection")
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
        return new CompletedRepairStatusCollection(CompletedRepairStatus::visible()->get());
    }
}
