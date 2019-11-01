<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Admin;

class AdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    public function testUserCantLoginWithoutUsername()
    {
        $response = $this->json(
            'POST',
            '/api/auth/login',
            []
        );

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'O campo usuário é obrigatório.',
        ]);
    }

    public function testUserCantLoginWithoutPassword()
    {
        $response = $this->json(
            'POST',
            '/api/auth/login',
            [
                'username' => $this->admin->username,
            ]
        );

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'O campo senha é obrigatório.',
        ]);
    }

    public function testUserCantLoginWithWrongUsername()
    {
        $response = $this->json(
            'POST',
            '/api/auth/login',
            [
                'username' => 'FailTest',
                'senha' => 'FailTest',
            ]
        );

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'O usuário informado não existe.'
        ]);
    }

    public function testUserCantLoginWithWrongPassword()
    {
        $response = $this->json(
            'POST',
            '/api/auth/login',
            [
                'username' => $this->admin->username,
                'senha' => 'FailTest',
            ]
        );

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'Senha incorreta.',
        ]);
    }

    public function testUserCanLogin()
    {
        $password = $this->admin->password;
        $this->admin->password = md5($this->admin->password);
        $this->admin->save();

        $response = $this->json(
            'POST',
            '/api/auth/login',
            [
                'username' => $this->admin->username,
                'senha' => $password,
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonFragment(['Login realizado com sucesso.']);
        $response->assertJsonStructure([
            'username',
            'user',
            'message',
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->admin->forceDelete();
    }
}
