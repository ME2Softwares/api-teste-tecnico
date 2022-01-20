<?php

use App\Models\Quote;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class QuoteTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldSeeQuotes(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        Quote::factory()->count(10)->create();

        $this->actingAs($user)
            ->get('/quotes');

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'data' => [
                    '*' => [
                        'id',
                        'text',
                        'author',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'current_page',
                'total',
                'per_page',
                'to'
            ]
        ]);
    }

    public function testShouldSeeQuote(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $quote = Quote::factory()->create();

        $this->actingAs($user)
            ->get('/quotes/' . $quote->id);

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'id',
                'text',
                'author',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testShouldCreateQuote(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/quotes', [
                'text' => 'Test quote',
                'author' => 'Test author',
            ]);

        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'id',
                'text',
                'author',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testShouldUpdateQuote(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $quote = Quote::factory()->create();

        $this->actingAs($user)
            ->put('/quotes/' . $quote->id, [
                'text' => 'Test quote',
                'author' => 'Test author',
            ]);

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'id',
                'text',
                'author',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function testShouldDeleteQuote(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $quote = Quote::factory()->create();

        $this->actingAs($user)
            ->delete('/quotes/' . $quote->id);

        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'status',
            'response'
        ]);
    }

    public function testShouldNotSeeQuotesWithoutUser(): void
    {
        $this->get('/quotes');

        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'response'
        ]);
    }

    public function testShouldNotSeeAQuoteWithoutUser(): void
    {
        $quote = Quote::factory()->create();

        $this->get('/quotes/' . $quote->id);

        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'response'
        ]);
    }

    public function testShouldNotCreateQuoteWithoutUser(): void
    {
        $this->post('/quotes', [
            'text' => 'Test quote',
            'author' => 'Test author',
        ]);

        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'response'
        ]);
    }

    public function testShouldNotUpdateQuoteWithoutUser(): void
    {
        $quote = Quote::factory()->create();

        $this->put('/quotes/' . $quote->id, [
            'text' => 'Test quote',
            'author' => 'Test author',
        ]);

        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'response'
        ]);
    }

    public function testShouldNotDeleteQuoteWithoutUser(): void
    {
        $quote = Quote::factory()->create();

        $this->delete('/quotes/' . $quote->id);

        $this->seeStatusCode(401);
        $this->seeJsonStructure([
            'status',
            'response'
        ]);
    }

    public function testeShoultNotCreateQuoteWithInvalidData(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/quotes', [
                'text' => '',
                'author' => '',
            ]);

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'text',
                'author',
            ]
        ]);
    }

    public function testeShoultNotUpdateQuoteWithInvalidData(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $quote = Quote::factory()->create();

        $this->actingAs($user)
            ->put('/quotes/' . $quote->id, [
                'text' => '',
                'author' => '',
            ]);

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'status',
            'response' => [
                'text',
                'author',
            ]
        ]);
    }
}
