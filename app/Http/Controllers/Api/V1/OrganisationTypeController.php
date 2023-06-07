<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\OrganisationTypeCollection;
use App\Http\Resources\V1\OrganisationTypeResource;
use App\Models\OrganisationType;
use Illuminate\Http\Request;

/**
 * @OA\PathItem(
 *   path="/organisation_types/{uuid_or_code}",
 *   @OA\Parameter(ref="#/components/parameters/organisation_type_id_in_path_required")
 * )
 */
class OrganisationTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @OA\Get(
     *  path="/organisation_types",
     *  summary="Organisation types",
     *  description="Display a listing of organisation types",
     *  operationId="getOrganisationTypesList",
     *  tags={"Organisation types"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Parameter(in="query", name="per_page", example="10"),
     *  @OA\Parameter(in="query", name="page", example="1"),
     *  @OA\Parameter(in="query", name="limit", example="10"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/OrganisationTypeCollection")
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
        return new OrganisationTypeCollection(OrganisationType::getByQueryParameters());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\OrganisationType $organisationType
     * @return \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\JsonResource
     *
     * @OA\Get(
     *  path="/organisation_types/{uuid_or_code}",
     *  summary="Organisation types",
     *  description="Get a organisation type",
     *  operationId="getOrganisationType",
     *  tags={"Organisation types"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/OrganisationTypeResource")
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
    public function show(OrganisationType $organisationType)
    {
        return new OrganisationTypeResource($organisationType);
    }
}
