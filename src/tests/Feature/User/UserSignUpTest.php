<?php

namespace Tests\Feature\User;

use App\Models\Currency;
use App\Models\User;
use App\Responders\Message;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

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
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post', $this->url, ['Authorization' => 'Bearer ' . $token]);
        $response->assertJson(['message' => Message::ONLY_GUEST_USER]);
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

        $response = $this->callRequest('post',$this->url,$attributes);

        $response->assertStatus(200);
    }

    public function test_email_is_required_for_signup()
    {
        $response = $this->callRequest('post',$this->url,[
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ]);

        $response->assertJson([
            'errors' => [
                'email' => [
                    Message::EMAIL_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_email_should_be_unique()
    {
        $response = $this->callRequest('post',$this->url,[
            'email' => $this->user->email,
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ]);

        $response->assertJson([
            'errors' => [
                'email' => [
                    Message::EMAIL_IS_UNIQUE
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_email_should_have_specific_format()
    {

        $response = $this->callRequest('post',$this->url,[
            'email' => 'email',
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ]);

        $response->assertJson([
            'errors' => [
                'email' => [
                    Message::EMAIL_HAS_FORMAT
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_password_is_required_for_signup()
    {
        $response = $this->callRequest('post',$this->url,[
            'email' => $this->email,
            'username' => $this->password,
            'currency_id' => $this->currency->id
        ]);

        $response->assertJson([
            'errors' => [
                'password' => [
                    Message::PASSWORD_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_password_should_not_less_than_6_characters()
    {
        $response = $this->callRequest('post',$this->url,[
            'email' => $this->email,
            'username' => $this->username,
            'password' => 'pass',
            'currency_id' => $this->currency->id
        ]);

        $response->assertJson([
            'errors' => [
                'password' => [
                    Message::PASSWORD_SHOULD_BE_LEAST_6_CHAR
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_currency_id_is_required()
    {
        $response = $this->callRequest('post', $this->url,[
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password
        ]);

        $response->assertJson([
            'errors' => [
                'currency_id' => [
                    Message::CURRENCY_ID_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_currency_id_should_exist_in_table()
    {
        $response = $this->callRequest('post',$this->url,[
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => 123456
        ]);

        $response->assertJson([
            'errors' => [
                'currency_id' => [
                    Message::CURRENCY_ID_SHOULD_EXIST_IN_TABLE
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_currency_id_should_be_integer()
    {
        $response = $this->callRequest('post',$this->url,[
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'currency_id' => 'string_currency_id'
        ]);

        $response->assertJson([
            'errors' => [
                'currency_id' => [
                    Message::CURRENY_ID_SHOULD_BE_INTEGER
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_username_is_required()
    {
        $response = $this->callRequest('post',$this->url,[
            'email' => $this->email,
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ]);

        $response->assertJson([
            'errors' => [
                'username' => [
                    Message::USERNAME_IS_REQUIRED
                ]
            ]
        ])->assertStatus(422);
    }

    public function test_username_should_not_less_than_6_characters()
    {
        $response = $this->callRequest('post',$this->url,[
            'email' => $this->email,
            'username' => 'name',
            'password' => $this->password,
            'currency_id' => $this->currency->id
        ]);

        $response->assertJson([
            'errors' => [
                'username' => [
                    Message::USERNAME_SHOULD_BE_LEAST_6_CHAR
                ]
            ]
        ])->assertStatus(422);
    }
}
