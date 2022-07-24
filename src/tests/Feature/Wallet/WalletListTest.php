<?php

namespace Tests\Feature\Wallet;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tests\Utilities\MiddlewareMessage;

class WalletListTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Currency */
    protected Currency $currency;

    /** @var string */
    protected string $url, $name, $correct_inventory, $incorrect_inventory, $new_name;

    public function setUp(): void
    {
        parent::setUp();

        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->wallets = Wallet::factory(5)->create([
            'user_id' => $this->user->id,
        ]);
        $this->url = $this->wallets[0]->path;
    }

    public function test_an_unautenticated_user_cant_get_wallets_list()
    {
        $response = $this->callRequest('get', $this->url,);
        $response->assertJson(['message' => MiddlewareMessage::AUTHENTICATED]);
    }

    public function test_a_signed_in_owner_user_can_get_list_of_wallets()
    {
        $token = $this->generateToken($this->user);
        $response = $this->callRequest(
            'get',
            $this->url,
            [
                'Authorization' => 'Bearer ' . $token
            ]
        );
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->has('data.items', 5));
    }
}
