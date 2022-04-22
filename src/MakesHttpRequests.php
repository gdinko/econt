<?php

namespace Gdinko\Econt;

use Gdinko\Econt\Exceptions\EcontException;
use Illuminate\Support\Facades\Http;

trait MakesHttpRequests
{
    public function get($uri)
    {
        return $this->request('get', $uri);
    }

    public function post($uri, array $data = [])
    {
        return $this->request('post', $uri, $data);
    }

    public function put($uri, array $data = [])
    {
        return $this->request('put', $uri, $data);
    }

    public function request($verb, $uri, $data = [])
    {
        $response = Http::timeout($this->timeout)
            ->baseUrl($this->baseUri)
            ->{$verb}($uri, $data)
            ->throw(function ($response, $e) {
                throw new EcontException(
                    $e->getMessage(),
                    $e->getCode(),
                    $response->json()
                );
            });

        return $response->json();
    }
}
