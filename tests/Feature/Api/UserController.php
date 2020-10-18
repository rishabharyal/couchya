<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserController extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp():void {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * @author Rishabh Aryal <rish.aryal@gmail.com>
     * @return void
     * Tests if the user has been successfully 
     * logged in, and returns the
     * generated token
     */
    public function testLoginGivesTokenBack()
    {
        $response = $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJson([
            'success' => true,
            'name' => $this->user->name,
            'email' => $this->user->email
        ]);
    }

    public function testRegisterGivesTokenAndUserInfo() {
        $newUser = User::factory()->make();
        $response = $this->post('/api/register', [
            'email' => $newUser->email,
            'password' => 'password',
            'name' => $newUser->name
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'name' => $newUser->name,
            'email' => $newUser->email
        ]);
    }
}
