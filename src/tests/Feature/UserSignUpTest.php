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

class UserSignUpTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Currency
     */
    protected Currency $currency;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var string
     */
    protected string $email, $username, $password, $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->email = 'user@gmail.com';
        $this->username = 'username';
        $this->password = 'password';
        $this->url = $this->user->signup_path;
    }

    public function test_a_singed_in_user_cant_signup()
    {
        Artisan::call('passport:install');

        /** @var User */
        $user = Passport::actingAs($this->user);
        $token = $user->createToken('Api token')->accessToken;
        $response = $this->makeApiResponse(['Authorization' => 'Bearer ' . $token], $this->url);
        $response->assertJson(['message' => MiddlewareMessage::GUEST]);
    }

    public function test_a_user_can_signup()
    {
        Artisan::call('passport:install');

        $attributes = [
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ];

        $response = $this->makeApiResponse($attributes,$this->url);

        $response->assertStatus(200);
    }

    public function test_email_is_required_for_signup()
    {
        $response = $this->makeApiResponse([
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'email' => [
                    ValidationMessage::EMAIL_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_email_should_be_unique()
    {
        $response = $this->makeApiResponse([
            'email' => $this->user->email,
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'email' => [
                    ValidationMessage::EMAIL_IS_UNIQUE
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_email_should_have_specific_format()
    {

        $response = $this->makeApiResponse([
            'email' => 'email',
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'email' => [
                    ValidationMessage::EMAIL_HAS_FORMAT
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_password_is_required_for_signup()
    {
        $response = $this->makeApiResponse([
            'email' => $this->email,
            'username' => $this->password,
            'currency_id' => $this->currency->id
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'password' => [
                    ValidationMessage::PASSWORD_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_password_should_not_less_than_6_characters()
    {
        $response = $this->makeApiResponse([
            'email' => $this->email,
            'username' => $this->username,
            'password' => 'pass',
            'currency_id' => $this->currency->id
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'password' => [
                    ValidationMessage::PASSWORD_SHOULD_BE_LEAST_6_CHAR
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_currency_id_is_required()
    {
        $response = $this->makeApiResponse([
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'currency_id' => [
                    ValidationMessage::CURRENCY_ID_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_currency_id_should_exist_in_table()
    {
        $response = $this->makeApiResponse([
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => 123456
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'currency_id' => [
                    ValidationMessage::CURRENCY_ID_SHOULD_EXIST_IN_TABLE
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_currency_id_should_be_integer()
    {
        $response = $this->makeApiResponse([
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => 'string_currency_id'
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'currency_id' => [
                    ValidationMessage::CURRENY_ID_SHOULD_BE_INTEGER
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_username_is_required()
    {
        $response = $this->makeApiResponse([
            'email' => $this->email,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'username' => [
                    ValidationMessage::USERNAME_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_username_should_not_less_than_6_characters()
    {
        $response = $this->makeApiResponse([
            'email' => $this->email,
            'username' => 'name',
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ],$this->url);

        $response->assertJson([
            'errors' => [
                'username' => [
                    ValidationMessage::USERNAME_SHOULD_BE_LEAST_6_CHAR
                ]
            ]
        ])->assertStatus(422);
    }
}
