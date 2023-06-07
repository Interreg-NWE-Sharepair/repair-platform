<?php

namespace App\Http\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class RestartersApiClient extends AbstractClient
{
    const API_GET_GROUPS = '/networks/{network_id}/groups';
    const API_GET_EVENTS = '/networks/{network_id}/events';
    const API_GET_GROUP_BY_ID = '/groups/{id}';
    const API_GET_GROUP_EVENTS_BY_ID = '/groups/{id}/events';

    public function getGroups(?string $afterDate = null)
    {
        $url = $this->getUrl(self::API_GET_GROUPS, [5]);
        if ($afterDate){
            $url .= '?updated_start='.$afterDate;
        }

        $response = $this->getHttpClient()->get($url);

        if ($response->successful()){
            $json = $response->json();

            return $json['data'] ?? [];
        }

        $response->throw();
    }

    public function getGroupById($id)
    {
        $response = $this->getHttpClient()->get($this->getUrl(self::API_GET_GROUP_BY_ID, [$id]));

        if ($response->successful()){
            $json = $response->json();

            return $json['data'] ?? null;
        }

        $response->throw();
    }

    public function getEventsByGroup($groupId)
    {
        $response = $this->getHttpClient()->get($this->getUrl(self::API_GET_GROUP_EVENTS_BY_ID, [$groupId]));

        if ($response->successful()){
            $json = $response->json();

            return $json['data'] ?? [];
        }

        $response->throw();
    }

    public function getEvents(?string $afterDate = null)
    {
        $url = $this->getUrl(self::API_GET_EVENTS, [5]);
        if ($afterDate){
            $url .= '?updated_start='.$afterDate;
        }

        $response = $this->getHttpClient()->get($url);

        if ($response->successful()){
            $json = $response->json();

            return $json['data'] ?? [];
        }

        $response->throw();
    }

    protected function configureHttpClient(PendingRequest $client): PendingRequest
    {
        $client->baseUrl(config('restarters-api.url'));
        $client->withToken(config('restarters-api.token'));

        return $client;
    }
}
