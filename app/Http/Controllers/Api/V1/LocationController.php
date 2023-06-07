<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\SuggestNewLocationRequest;
use App\Http\Resources\V1\LocationCollection;
use App\Http\Resources\V1\LocationResource;
use App\Models\Location;
use App\Services\LocationSuggestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

/**
 * @OA\PathItem(
 *   path="/locations/{uuid_or_slug}",
 *   @OA\Parameter(ref="#/components/parameters/location_id_in_path_required")
 * ),
 * @OA\PathItem(
 *   path="/locations/{uuid_or_slug}/suggestions",
 *   @OA\Parameter(ref="#/components/parameters/location_id_in_path_required")
 * )
 */
class LocationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response|JsonResource
     *
     * @OA\Get(
     *  path="/locations",
     *  summary="Locations",
     *  description="Display a listing of locations",
     *  operationId="getLocationsList",
     *  tags={"Locations"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Parameter(in="query", name="organisation_types", @OA\Examples(summary="Uuid", example="7b404df9-bdcb-4a69-8588-507242d25044", value="7b404df9-bdcb-4a69-8588-507242d25044"), @OA\Examples(summary="Code", example="professional_repairer", value="professional_repairer"), @OA\Examples(summary="Multiple options", example="multiple", value="c3f479e8-e35b-43bf-83b9-64a0b4ae1a6e, 7b404df9-bdcb-4a69-8588-507242d25044"), description="You can add multiple comma seperated types"),
     *  @OA\Parameter(in="query", name="product_categories", @OA\Examples(summary="Uuid", example="3349056e-2c68-4a51-a30c-91c5fed8c033", value="3349056e-2c68-4a51-a30c-91c5fed8c033"), @OA\Examples(summary="Code", example="desktop-computer", value="desktop-computer"), @OA\Examples(summary="Multiple options", example="multiple", value="3349056e-2c68-4a51-a30c-91c5fed8c033, 63ed5d48-a85f-4f95-883c-7cf09819fe54"), description="You can add multiple comma seperated categories", deprecated=true),
     *  @OA\Parameter(in="query", name="device_types", @OA\Examples(summary="Uuid", example="3349056e-2c68-4a51-a30c-91c5fed8c033", value="3349056e-2c68-4a51-a30c-91c5fed8c033"), @OA\Examples(summary="Code", example="desktop-computer", value="desktop-computer"), @OA\Examples(summary="Multiple options", example="multiple", value="3349056e-2c68-4a51-a30c-91c5fed8c033, 63ed5d48-a85f-4f95-883c-7cf09819fe54"), description="You can add multiple comma seperated types"),
     *  @OA\Parameter(in="query", name="bbox", example="51.2867602,-0.5103751,51.6918741,0.3340155", description="To search by a box of latitude and longitude: minLat, minLong, maxLat, maxLong"),
     *  @OA\Parameter(in="query", name="per_page", example="10"),
     *  @OA\Parameter(in="query", name="page", example="1"),
     *  @OA\Parameter(in="query", name="limit", example="10"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/LocationCollection")
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
        return new LocationCollection(Location::with('organisationType', 'organisation', 'organisation.deviceTypes.parent', 'organisation.activitySectors', 'organisation.contactDetails', 'media', 'organisation.organisationLocales')
                                              ->search($request)->getByQueryParameters());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Location $location
     * @return Response|JsonResource
     *
     * @OA\Get(
     *  path="/locations/{uuid_or_slug}",
     *  summary="Location",
     *  description="Get a location",
     *  operationId="getLocation",
     *  tags={"Locations"},
     *  @OA\Parameter(in="query", name="locales", example="nl", description="You can add multiple comma seperated locales"),
     *  @OA\Response(
     *           response=200,
     *           description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/LocationResource")
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
    public function show(Location $location)
    {
        return new LocationResource($location);
    }

    /**
     * Suggest a new location
     *
     * @OA\Post (
     *  path="/locations/suggestions",
     *  summary="Suggest a new location",
     *  description="Suggest a new location",
     *  operationId="suggestLocation",
     *  tags={"Locations suggestion"},
     *  @OA\RequestBody(
     *      required=true,
     *      description="Create new suggestion",
     *      @OA\JsonContent(ref="#/components/schemas/LocationSuggestion")
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
     * @param \App\Models\Location $location
     * @return JsonResponse
     */
    public function suggestNew(SuggestNewLocationRequest $request, LocationSuggestionService $locationSuggestionService)
    {
        $data = $request->validated();
        $locationSuggestion = $locationSuggestionService->createNewLocationSuggestion($data);

        return response()->json(['success' => true]);
    }

    /**
     * Suggest a change for the a location
     *
     * @OA\Post (
     *  path="/locations/{uuid_or_slug}/suggestions",
     *  summary="Suggest a change to a location",
     *  description="Suggest a change to a location",
     *  operationId="suggestNewLocation",
     *  tags={"Locations suggestion"},
     *  @OA\RequestBody(
     *      required=true,
     *      description="Suggest a change to a location",
     *      @OA\JsonContent(ref="#/components/schemas/LocationSuggestion")
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
     * @param Request $request
     * @param \App\Models\Location $location
     * @return JsonResponse
     */
    public function suggestChange(SuggestNewLocationRequest $request,
        Location $location,
        LocationSuggestionService $locationSuggestionService
    ) {
        $data = $request->validated();
        $locationSuggestion = $locationSuggestionService->createNewLocationSuggestion($data, $location);

        return response()->json(['success' => true]);
    }
}
