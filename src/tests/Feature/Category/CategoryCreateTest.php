<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Utilities\MiddlewareMessage;
use Tests\Utilities\ValidationMessage;

class CategoryCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @var Category */
    protected Category $category;

    /** @var User */
    protected User $user;

    /** @var string */
    protected string $url,$name;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(1)[0];
        $this->category = $this->createCategory(1, ['user_id' => $this->user->id])[0];
        $this->url = $this->category->path;
        $this->name = 'Bill';
    }

    public function test_an_unautenticated_user_cant_create_category()
    {
        $response = $this->callRequest('post', $this->url, ['name' => $this->name]);
        $response->assertJson(['message' => MiddlewareMessage::AUTHENTICATED]);
    }

    public function test_name_is_required()
    {
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post',$this->url,[
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertJson(['message' => ValidationMessage::CATEGORY_NAME_IS_REQUIRED]);
    }

    public function test_a_user_cant_create_a_wallet_with_duplicated_name()
    {
        $token = $this->generateToken($this->user);
        $response = $this->callRequest('post', $this->url,[
            'Authorization' => 'Bearer ' . $token,
            'name' => $this->category->name,
        ]);
        $response->assertJson(['message' => ValidationMessage::CATEGORY_NAME_SHOULD_BE_UNIQUE]);
    }
}
