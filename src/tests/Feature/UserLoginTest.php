<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Tests\Utilities\MiddlewareMessage;
use Tests\Utilities\ValidationMessage;

class UserLoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Currency */
    protected Currency $currency;

    /** @var string */
    protected string $email, $password, $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->email = 'user@gmail.com';
        $this->password = 'password';
        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->url = $this->user->login_path;
    }

    public function test_a_singed_in_user_cant_login()
    {
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post', $this->url, ['Authorization' => 'Bearer ' . $token]);
        $response->assertJson(['message' => MiddlewareMessage::GUEST]);
    }

    public function test_a_user_can_login()
    {
        Artisan::call('passport:install');

        $attributes  = [
            'email' => $this->user->email,
            'password' => $this->password,
        ];

        $response = $this->callRequest('post', $this->url, $attributes);
        $response->assertStatus(200);
    }

    public function test_email_password_should_be_correct()
    {
        $attributes  = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        $response = $this->callRequest('post', $this->url, $attributes,);
        $response->assertStatus(422);
    }

    public function test_email_is_required_for_login()
    {
        $response = $this->callRequest('post', $this->url, [
            'password' => $this->password,
        ]);

        $response->assertJson([
            'errors' => [
                'email' => [
                    ValidationMessage::EMAIL_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_password_is_required_for_login()
    {
        $response = $this->callRequest('post', $this->url, [
            'email' => $this->email,
        ]);

        $response->assertJson([
            'errors' => [
                'password' => [
                    ValidationMessage::PASSWORD_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }
}
