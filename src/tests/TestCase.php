<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  array $attributes
     * @return mixed
     */
    protected function makeApiResponse(array $attributes = [],string $url): mixed
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post($url, $attributes);
        return $response;
    }
}
