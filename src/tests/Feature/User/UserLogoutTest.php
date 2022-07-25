<?php

namespace Tests\Feature\User;

use App\Models\Currency;
use App\Models\User;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserLogoutTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var string */
    protected string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->url = $this->user->logout_path;
    }

    public function test_a_signed_in_user_can_logout()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest('post', $this->url);
        $response->assertStatus(200);
    }

    public function test_a_not_signed_in_user_cant_logout()
    {
        $response = $this->callRequest('post', $this->url);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }
}
