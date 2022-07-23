<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  array $attributes
     * @return mixed
     */
    protected function callRequest($method = 'post',string $url,array $attributes = []): mixed
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->{$method}($url, $attributes);
        return $response;
    }

    /**
     * @param  User $user
     * @return string
     */
    protected function generateToken(User $user): string
    {
        Artisan::call('passport:install');
        /** @var User */
        $user = Passport::actingAs($user);
        $token = $user->createToken('Api token')->accessToken;
        return $token;
    }
}
