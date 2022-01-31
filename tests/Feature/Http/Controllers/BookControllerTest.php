<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BookController
 */
class BookControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $books = Book::factory()->count(3)->create();

        $response = $this->get(route('book.index'));

        $response->assertOk();
        $response->assertViewIs('book.index');
        $response->assertViewHas('books');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('book.create'));

        $response->assertOk();
        $response->assertViewIs('book.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BookController::class,
            'store',
            \App\Http\Requests\BookStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $title = $this->faker->sentence(4);
        $description = $this->faker->text;
        $coverImage = $this->faker->word;

        $response = $this->post(route('book.store'), [
            'title' => $title,
            'description' => $description,
            'coverImage' => $coverImage,
        ]);

        $books = Book::query()
            ->where('title', $title)
            ->where('description', $description)
            ->where('coverImage', $coverImage)
            ->get();
        $this->assertCount(1, $books);
        $book = $books->first();

        $response->assertRedirect(route('book.index'));
        $response->assertSessionHas('book.id', $book->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $book = Book::factory()->create();

        $response = $this->get(route('book.show', $book));

        $response->assertOk();
        $response->assertViewIs('book.show');
        $response->assertViewHas('book');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $book = Book::factory()->create();

        $response = $this->get(route('book.edit', $book));

        $response->assertOk();
        $response->assertViewIs('book.edit');
        $response->assertViewHas('book');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BookController::class,
            'update',
            \App\Http\Requests\BookUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $book = Book::factory()->create();
        $title = $this->faker->sentence(4);
        $description = $this->faker->text;
        $coverImage = $this->faker->word;

        $response = $this->put(route('book.update', $book), [
            'title' => $title,
            'description' => $description,
            'coverImage' => $coverImage,
        ]);

        $book->refresh();

        $response->assertRedirect(route('book.index'));
        $response->assertSessionHas('book.id', $book->id);

        $this->assertEquals($title, $book->title);
        $this->assertEquals($description, $book->description);
        $this->assertEquals($coverImage, $book->coverImage);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $book = Book::factory()->create();

        $response = $this->delete(route('book.destroy', $book));

        $response->assertRedirect(route('book.index'));

        $this->assertDeleted($book);
    }
}
