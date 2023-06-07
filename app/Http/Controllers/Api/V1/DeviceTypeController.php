<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\DeviceTypeCollection;
use App\Http\Resources\V1\DeviceTypeResource;
use App\Models\DeviceType;
use Illuminate\Http\Request;

/**
 * @OA\PathItem(
 *   path="/product_categories/{uuid_or_code}",
 *   @OA\Parameter(ref="#/components/parameters/product_category_id_in_path_required")
 * )
 */
class DeviceTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @OA\Get(
     *  path="/product_categories",
     *  summary="Product categories",
     *  description="Display a listing of product categories (new endpoint: /device_types)",
     *  operationId="getProductCategoriesList",
     *  tags={"Device types"},
     *  deprecated=true,
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Parameter(in="query", name="per_page", example="10"),
     *  @OA\Parameter(in="query", name="page", example="1"),
     *  @OA\Parameter(in="query", name="limit", example="10"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/DeviceTypeCollection")
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
     *
     * @OA\Get(
     *  path="/device_types",
     *  summary="Device types",
     *  description="Display a listing of device types",
     *  operationId="getDeviceTypesList",
     *  tags={"Device types"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Parameter(in="query", name="per_page", example="10"),
     *  @OA\Parameter(in="query", name="page", example="1"),
     *  @OA\Parameter(in="query", name="limit", example="10"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/DeviceTypeCollection")
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
        return new DeviceTypeCollection(DeviceType::with('parent')->showOnMapping()->getByQueryParameters());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\DeviceType $deviceType
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @OA\Get(
     *  path="/product_categories/{uuid_or_code}",
     *  summary="Product category",
     *  description="Get a product category (new endpoint: /device_types)",
     *  operationId="getProductCategory",
     *  tags={"Device types"},
     *  deprecated=true,
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/DeviceTypeResource")
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
     * @OA\Get(
     *  path="/device_types/{uuid_or_code}",
     *  summary="Device type",
     *  description="Get a device type",
     *  operationId="getDeviceType",
     *  tags={"Device types"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/DeviceTypeResource")
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
    public function show(DeviceType $deviceType)
    {
        if (!$deviceType->show_on_mapping){
            return abort(404);
        }
        return new DeviceTypeResource($deviceType);
    }
}
