<?php

namespace App\Http\Clients;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

abstract class AbstractClient
{
    protected string $baseUrl;

    protected PendingRequest $httpClient;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->createHttpClient();
    }

    protected function createHttpClient()
    {
        $timeout = 15;
        if (config('app.debug')) {
            $timeout = 1500;
        }

        $httpClient = Http::timeout($timeout);

        $this->configureHttpClient($httpClient);

        $this->httpClient = $httpClient;

        return $httpClient;
    }

    protected abstract function configureHttpClient(PendingRequest $client): PendingRequest;

    protected function getUrl(string $api, $parameters = null)
    {
        $url = Str::start($api, '/');

        $this->addUrlParameters($url, $parameters);

        return $url;
    }

    public function getHttpClient()
    {
        if ($this->httpClient) {
            return $this->httpClient;
        }

        return $this->createHttpClient();
    }

    private function parseUrlParameters(&$parameters)
    {
        $this->urlParametersAsArray($parameters);

        $parameters = array_filter($parameters, function($value) {
            return !is_null($value) && $value !== '';
        });

        $parameters = array_map(function ($value) {
            $value = urlencode($value);
            return str_replace('.', '%2E', $value);
        }, $parameters);
    }

    private function urlParametersAsArray(&$parameters)
    {
        if (is_array($parameters)) {
            return;
        }

        if ($parameters === null) {
            $parameters = [];
            return;
        }

        $parameters = [$parameters];
        return;
    }

    private function addUrlParameters(&$url, $parameters)
    {
        $this->parseUrlParameters($parameters);

        preg_match_all('/\{(.*?)\}/', $url, $matches);
        [$parameterKeys, $parameterNames] = $matches;

        foreach ($parameters as $key => $parameter){
            //using the provided parameter key
            $parameterKey = array_search($key, $parameterNames, true);
            if ($parameterKey !== false){
                $this->fillParameter($url, $parameterKey, $parameter, $parameterKeys, $parameterNames);
                continue;
            }

            //using the first found parameter key
            $parameterKey = array_key_first($parameterKeys);
            $this->fillParameter($url, $parameterKey, $parameter, $parameterKeys, $parameterNames);
        }
    }

    private function fillParameter(&$url, $parameterKey, $parameter, &$parameterKeys, &$parameterNames){
        $url = Str::of($url)->replace($parameterKeys[$parameterKey], $parameter);
        unset($parameterKeys[$parameterKey]);
        unset($parameterNames[$parameterKey]);
    }

}
