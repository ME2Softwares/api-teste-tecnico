<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class UserRegisterTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldRegisterUser(): void
    {
        $this->post(
            '/users',
            [
                'name'     => 'Peter Parker',
                'email'    => 'spiderman@gmail.com',
                'password' => 'Strong@p4sS'
            ]
        );
        $this->seeStatusCode(201);
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

        $this->seeInDatabase('users', [
            'name'  => 'Peter Parker',
            'email' => 'spiderman@gmail.com',
        ]);
    }

    public function testShouldSeeErrorWhenRegisteringUserWithEmailAlreadyExists(): void
    {
        $this->testShouldRegisterUser();

        $this->post(
            '/users',
            [
                'name'     => 'Peter Parker',
                'email'    => 'spiderman@gmail.com',
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

    public function testShouldSeeErrorWhenRegisteringUserWithPasswordLessThan6Characters(): void
    {
        $this->post(
            '/users',
            [
                'name'     => 'Peter Parker',
                'email'    => 'spiderman@gmail.com',
                'password' => 'weak'
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

    public function testShouldSeeErrorWhenRegisteringUserWithInvalidEmail(): void
    {
        $this->post(
            '/users',
            [
                'name'     => 'Peter Parker',
                'email'    => 'spiderman',
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

    public function testShouldSeeErrorWhenRegisteringUserWithInvalidName(): void
    {
        $this->post(
            '/users',
            [
                'name'     => '',
                'email'    => 'spiderman@gmail.com',
                'password' => 'Strong@p4sS'
            ]
        );

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'name'
            ]
        ]);
    }
}
