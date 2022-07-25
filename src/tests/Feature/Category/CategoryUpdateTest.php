<?php

namespace Tests\Feature\Wallet;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\Responders\Message;
use Laravel\Passport\Passport;

class CategoryUpdateTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Category */
    protected Category $currency;

    /** @var string */
    protected string $url, $name, $method;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->category = $this->createCategory(1, ['user_id' => $this->user->id])[0];
        $this->url = $this->category->path . '/' . $this->category->id;
        $this->method = 'put';
        $this->name = 'Bill';
    }

    public function test_an_unautenticated_user_cant_update_category()
    {
        $response = $this->callRequest($this->method, $this->url, []);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_name_is_required()
    {
        Passport::actingAs($this->user);
        Gate::define('check-category-own', function () {
            return true;
        });
        $response = $this->callRequest($this->method, $this->url);
        $response->assertJson(['message' => Message::CATEGORY_NAME_IS_REQUIRED]);
    }

    public function test_just_wallet_owner_can_update_category()
    {
        Passport::actingAs($this->createUser(1)[0]);
        $response = $this->callRequest($this->method, $this->url, [
            'name' => $this->name,
        ]);
        $response->assertJson(['error' => Message::ONLY_CATEGORY_OWNER_CAN_GET_IT])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_update_category()
    {
        Passport::actingAs($this->user);
        Gate::define('check-category-own', function () {
            return true;
        });
        $response = $this->callRequest($this->method, $this->url, [
            'name' => $this->name,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id,
            'name' => $this->name,
        ]);
    }

    public function test_a_user_cant_update_a_wallet_with_duplicated_name()
    {
        Passport::actingAs($this->user);
        $category2 = $this->createCategory(1, [
            'user_id' => $this->user->id,
            'name' => $this->name
        ])[0];
        $response = $this->callRequest($this->method, $this->url, [
            'name' => $category2->name,
        ]);
        $response->assertJson(['message' => Message::CATEGORY_NAME_SHOULD_BE_UNIQUE]);
    }
}
