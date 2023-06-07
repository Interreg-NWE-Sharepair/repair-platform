<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\ActivitySectorCollection;
use App\Http\Resources\V1\ActivitySectorResource;
use App\Models\ActivitySector;
use Illuminate\Http\Request;

/**
 * @OA\PathItem(
 *   path="/activity_sectors/{uuid_or_code}",
 *   @OA\Parameter(ref="#/components/parameters/activity_sector_id_in_path_required")
 * )
 */
class ActivitySectorController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @OA\Get(
     *  path="/activity_sectors",
     *  summary="Activity sectors",
     *  description="Display a listing of activity sectors",
     *  operationId="getActivitySectorList",
     *  tags={"Activity sectors"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Parameter(in="query", name="per_page", example="10"),
     *  @OA\Parameter(in="query", name="page", example="1"),
     *  @OA\Parameter(in="query", name="limit", example="10"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/ActivitySectorCollection")
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
        return new ActivitySectorCollection(ActivitySector::getByQueryParameters());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProductCategory $activitySector
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @OA\Get(
     *  path="/activity_sectors/{uuid_or_code}",
     *  summary="Activity sectors",
     *  description="Get an activity sector",
     *  operationId="getActivitySector",
     *  tags={"Activity sectors"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/ActivitySectorResource")
     *        ),
     *  @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *  ),
     *  @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *  ),
     * )
     */
    public function show(ActivitySector $activitySector)
    {
        return new ActivitySectorResource($activitySector);
    }
}
