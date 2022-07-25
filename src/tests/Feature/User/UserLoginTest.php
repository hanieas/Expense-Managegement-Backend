<?php

namespace Tests\Feature\User;

use App\Models\Currency;
use App\Models\User;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

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
        $this->user = $this->createUser(1)[0];
        $this->url = $this->user->login_path;
    }

    public function test_a_singed_in_user_cant_login()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest('post', $this->url);
        $response->assertJson(['message' => Message::ONLY_GUEST_USER]);
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

        $response = $this->callRequest('post', $this->url, $attributes);
        $response->assertStatus(422);
    }

    public function test_required_fields()
    {
        $response = $this->callRequest('post', $this->url);

        $response->assertInvalid([
            'email' => Message::EMAIL_IS_REQUIRED,
            'password' => Message::PASSWORD_IS_REQUIRED
        ])->assertStatus(422);
    }
}
