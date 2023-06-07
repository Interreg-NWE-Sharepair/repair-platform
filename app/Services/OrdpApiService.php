<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use function config;

class OrdpApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('ordp.base_url');
    }

    public function getProductCategories()
    {
        $request = $this->getClient()->post('/', [
            'query' => 'query query {
                standard {
                    productCategories {
                        id,
                        label,
                        notes
                    }
                }
            }',
            "variables" => [],
        ])->throw();

        return $request->collect('data.standard.productCategories');
    }

    public function getGuidelines($search = null, $page = 1, $perPage = 3)
    {
        $request = $this->getClient()->post('/', [
            'query' => 'query Guidelines($search: String!, $perPage: Int, $page: Int) {
                guidelines(search: $search, perPage: $perPage, page: $page) {
                    id
                    description
                    imageURL
                    keyword
                    issued
                    landingPage
                    license
                    title
                }
            }',
            "variables" => ['search' => $search, 'perPage' => $perPage, 'page' => $page],
        ])->throw();

        return $request->collect('data.guidelines');
    }

    public function get3dResources($search = null, $page = 1, $perPage = 3)
    {
        $request = $this->getClient()->post('/', [
            'query' => 'query Guidelines($search: String!, $perPage: Int, $page: Int) {
                guidelines(search: $search, perPage: $perPage, page: $page) {
                    id
                    description
                    imageURL
                    keyword
                    issued
                    landingPage
                    license
                    title
                }
            }',
            "variables" => ['search' => $search, 'perPage' => $perPage, 'page' => $page],
        ])->throw();

        return $request->collect('data.guidelines');
    }

    private function getClient()
    {
        return Http::acceptJson()->baseUrl($this->baseUrl);
    }
}
