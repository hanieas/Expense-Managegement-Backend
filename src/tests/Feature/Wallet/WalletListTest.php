<?php

namespace Tests\Feature\Wallet;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Responders\Message;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;

class WalletListTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Currency */
    protected Currency $currency;

    /** @var string */
    protected string $url, $name;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->wallets = Wallet::factory(5)->create([
            'user_id' => $this->user->id,
        ]);
        $this->url = $this->wallets[0]->path;
    }

    public function test_an_unautenticated_user_cant_get_wallets_list()
    {
        $response = $this->callRequest('get', $this->url,);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_a_signed_in_owner_user_can_get_list_of_wallets()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest(
            'get',
            $this->url,
        );
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->has('data.items', 5));
    }
}
