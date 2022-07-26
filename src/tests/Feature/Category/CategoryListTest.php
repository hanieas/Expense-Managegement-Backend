<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Responders\Message;
use Laravel\Passport\Passport;

class CategoryListTest extends TestCase
{
    use DatabaseMigrations;

    /** @var User */
    protected User $user;

    /** @var Category */
    protected Category $currency;

    /** @var string */
    protected string $url;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->categories = $this->createCategory(5, ['user_id' => $this->user->id]);
        $this->url = $this->categories[0]->path;
        $this->method = 'get';
    }

    public function test_an_unautenticated_user_cant_get_categories()
    {
        $response = $this->callRequest($this->method, $this->url,);
        $response->assertJson(['message' => Message::ONLY_AUTHENTICATED_USER]);
    }

    public function test_a_signed_in_owner_user_can_get_list_of_categories()
    {
        Passport::actingAs($this->user);
        $response = $this->callRequest(
            $this->method,
            $this->url
        );
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('meta')
                ->has('data.items', 5));
    }
}
