<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\Utilities\ValidationMessage;

class AuthenticationTest extends TestCase
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
    protected string $email, $username, $password;

    /**
     * @param  array $attributes
     * @return mixed
     */
    private function makeApiResponse(array $attributes = []): mixed
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post($this->user->signup_path, $attributes);
        return $response;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->email = 'user@gmail.com';
        $this->username = 'username';
        $this->password = 'password';
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

        $response = $this->makeApiResponse($attributes);

        $response->assertStatus(200);
    }

    public function test_email_is_required_for_signup()
    {
        $response = $this->makeApiResponse([
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ]);

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
        ]);

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
        ]);

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
        ]);

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
        ]);

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
        ]);

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
        ]);

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
        ]);

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
        ]);

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
        ]);

        $response->assertJson([
            'errors' => [
                'username' => [
                    ValidationMessage::USERNAME_SHOULD_BE_LEAST_6_CHAR
                ]
            ]
        ])->assertStatus(422);
    }
}
