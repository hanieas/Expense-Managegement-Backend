<?php

namespace Tests\Feature\Wallet;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use Tests\Utilities\MiddlewareMessage;
use Tests\Utilities\ValidationMessage;

class CategoryDeleteTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Category */
    protected Category $currency;

    /** @var string */
    protected string $url, $method;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->category = $this->createCategory(1,['user_id'=>$this->user->id])[0];
        $this->url = $this->category->path.'/'.$this->category->id;
        $this->method = 'delete';
    }

    public function test_an_unautenticated_user_cant_delete_wallet()
    {
        $response = $this->callRequest($this->method, $this->url, []);
        $response->assertJson(['message' => MiddlewareMessage::AUTHENTICATED]);
    }

    public function test_just_wallet_owner_can_delete_category()
    {
        $token = $this->generateToken(User::factory()->create());
        $response = $this->callRequest($this->method, $this->url, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertJson(['error' => ValidationMessage::ONLY_CATEGORY_OWNER_CAN_GET_IT])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_delete_category()
    {
        $token = $this->generateToken($this->user);
        Gate::define('check-category-own', function () {
            return true;
        });
        $response = $this->callRequest($this->method, $this->url, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('categories',0);
    }
}
