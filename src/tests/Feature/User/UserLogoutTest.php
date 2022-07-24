<?php

namespace Tests\Feature\User;

use App\Models\Currency;
use App\Models\User;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post', $this->url, ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
    }

    public function test_a_not_signed_in_user_cant_logout()
    {
        $response = $this->callRequest('post', $this->url);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }
}
