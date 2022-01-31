<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): \Illuminate\Http\Response
    {
        $books = Book::all();

        return view('book.index', compact('books'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): \Illuminate\Http\Response
    {
        return view('book.create');
    }

    /**
     * @param \App\Http\Requests\BookStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookStoreRequest $request): \Illuminate\Http\Response
    {
        $book = Book::create($request->validated());

        $request->session()->flash('book.id', $book->id);

        return redirect()->route('book.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Book $book): \Illuminate\Http\Response
    {
        return view('book.show', compact('book'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Book $book): \Illuminate\Http\Response
    {
        return view('book.edit', compact('book'));
    }

    /**
     * @param \App\Http\Requests\BookUpdateRequest $request
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookUpdateRequest $request, Book $book): \Illuminate\Http\Response
    {
        $book->update($request->validated());

        $request->session()->flash('book.id', $book->id);

        return redirect()->route('book.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Book $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Book $book): \Illuminate\Http\Response
    {
        $book->delete();

        return redirect()->route('book.index');
    }
}
