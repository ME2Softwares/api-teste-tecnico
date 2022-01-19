<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UserLoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldLoginUser(): void
    {
        $user = User::factory()->create();

        $this->post(
            '/authenticate',
            [
                'email'    => $user->email,
                'password' => 'Strong@p4sS'
            ]
        );

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'id',
                'name',
                'email',
                'api_token',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testShouldSeeErrorWhenLoginUserWithInvalidCredentials(): void
    {
        $user = User::factory()->create();

        $this->post(
            '/authenticate',
            [
                'email'    => $user->email,
                'password' => 'Strong@p4sS1'
            ]
        );

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'message',
            'response'
        ]);
        $this->seeJsonContains([
            'status'  => false,
            'message' => 'Email ou Senha inválida',
        ]);
    }

    public function testShouldSeeErrorWhenLoginUserWithInvalidEmail(): void
    {
        $user = User::factory()->create();

        $this->post(
            '/authenticate',
            [
                'email'    => '',
                'password' => 'Strong@p4sS'
            ]
        );

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'email'
            ]
        ]);
    }

    public function testShouldSeeErrorWhenLoginUserWithInvalidPassword(): void
    {
        $user = User::factory()->create();

        $this->post(
            '/authenticate',
            [
                'email'    => $user->email,
                'password' => ''
            ]
        );

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'password'
            ]
        ]);
    }

    public function testShouldSeeErrorWhenLoginUserWithInvalidEmailAndPassword(): void
    {
        $user = User::factory()->create();

        $this->post(
            '/authenticate',
            [
                'email'    => '',
                'password' => ''
            ]
        );

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'email',
                'password'
            ]
        ]);
    }

    public function testShouldSeeErrorWhenLoginUserWithEmailNotFound(): void
    {
        $user = User::factory()->make();

        $this->post(
            '/authenticate',
            [
                'email'    => $user->email,
                'password' => 'Strong@p4sS'
            ]
        );

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'message',
            'response' => [
                'email'
            ]
        ]);
    }
}
