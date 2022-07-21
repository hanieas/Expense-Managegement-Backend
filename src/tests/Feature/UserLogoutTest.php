<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Tests\Utilities\MiddlewareMessage;

class UserLogoutTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Currency */
    protected Currency $currency;

    /** @var string */
    protected string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->url = $this->user->logout_path;
    }

    public function test_a_signed_in_user_can_logout()
    {
        Artisan::call('passport:install');

        /** @var User */
        $user = Passport::actingAs($this->user);
        $token = $user->createToken('Api token')->accessToken;
        $response = $this->makeApiResponse(['Authorization' => 'Bearer ' . $token], $this->url);
        $response->assertStatus(200);
    }

    public function test_a_not_signed_in_user_cant_logout()
    {
        $response = $this->makeApiResponse([], $this->url);
        $response->assertJson(['message' => MiddlewareMessage::AUTHENTICATED]);
    }
}
