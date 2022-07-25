<?php

namespace Tests\Feature\Category;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use App\Responders\Message;
use Laravel\Passport\Passport;

class CategoryShowTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->category = $this->createCategory(1, ['user_id' => $this->user->id, 'name' => 'Bill'])[0];
        $this->url = $this->category->path . '/' . $this->category->id;
    }

    public function test_an_unautenticated_user_cant_show_category()
    {
        $response = $this->callRequest('get', $this->url);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_just_wallet_owner_can_show_category()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest(
            'get',
            $this->url
        );
        $response->assertJson(['error' => Message::ONLY_CATEGORY_OWNER_CAN_GET_IT])
            ->assertStatus(403);
    }

    public function test_a_signed_in_owner_user_can_get_category()
    {
        Passport::actingAs($this->user);
        Gate::define('check-category-own', function () {
            return true;
        });
        $response = $this->callRequest(
            'get',
            $this->url
        );
        $response->assertStatus(200)
            ->assertJsonPath('data.name', $this->category->name);
    }
}
