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

        $adminDb = Admin::where('username', $this->admin->username)->first();

        $token = $adminDb->token;

        $response->assertStatus(200);
        $response->assertJsonFragment(['Login realizado com sucesso.']);
        $response->assertJsonStructure([
            'username',
            'user',
            'message',
        ]);
        $this->assertEquals($token, md5($response->headers->get('token')));
    }

    public function testSplashScreenWithoutToken()
    {
        $response = $this->json(
            'POST',
            '/api/auth/splash',
            [
                'token' => null,
            ]
        );

        $response->assertStatus(401);
        $response->assertJsonFragment(['Token vazio. Favor fazer login.']);
    }

    public function testSplashScreenWithInvalidToken()
    {
        $response = $this->json(
            'POST',
            '/api/auth/splash',
            [
                'token' => 'invalidToken',
            ]
        );

        $response->assertStatus(401);
        $response->assertJsonFragment(['Token inválido. Favor refazer login']);
    }

    public function testSplashScreenValidLogin()
    {
        $token = \Str::random(60);
        $this->admin->token = md5($token);
        $this->admin->save();
        $response = $this->json(
            'POST',
            '/api/auth/splash',
            [
                'token' => $token
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonFragment(['Login realizado com sucesso.']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->admin->forceDelete();
    }
}
