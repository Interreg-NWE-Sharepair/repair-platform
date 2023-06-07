<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Repair maps API",
 *    version="1.0.2",
 *    @OA\Contact(
 *       email="support@statik.be"
 *    ),
 * )
 * @OA\ExternalDocumentation(
 *          description="Changelog",
 *          url="changelog"
 *     )
 * @OA\Server(
 *      url=V1_CONST_HOST_LIVE,
 *      description="API Live Server"
 * )
 * @OA\Server(
 *      url=V1_CONST_HOST_STAGING,
 *      description="API Staging Server"
 * )
 *
 * @OA\Parameter(
 *   parameter="location_id_in_path_required",
 *   name="uuid_or_slug",
 *   @OA\Schema(
 *     type="string",
 *   ),
 *   in="path",
 *   required=true
 * )
 *
 * @OA\Parameter(
 *   parameter="organisation_type_id_in_path_required",
 *   name="uuid_or_code",
 *   @OA\Schema(
 *     type="string",
 *   ),
 *   in="path",
 *   required=true
 * )
 *
 * @OA\Parameter(
 *   parameter="product_category_id_in_path_required",
 *   name="uuid_or_code",
 *   @OA\Schema(
 *     type="string",
 *   ),
 *   in="path",
 *   required=true
 * )
 *
 * @OA\Parameter(
 *   parameter="repair_status_id_in_path_required",
 *   name="uuid_or_code",
 *   @OA\Schema(
 *     type="string",
 *   ),
 *   in="path",
 *   required=true
 * )
 *
 * @OA\Parameter(
 *   parameter="activity_sector_id_in_path_required",
 *   name="uuid_or_code",
 *   @OA\Schema(
 *     type="string",
 *   ),
 *   in="path",
 *   required=true
 * )
 */
class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
