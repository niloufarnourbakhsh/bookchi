<?php

namespace Modules\Book\Services;

use Illuminate\Support\Facades\DB;
use Modules\Book\Enums\ContributorRoleEnum;
use Modules\Book\Models\Attribute;
use Modules\Book\Models\Book;
use Throwable;

class BookService
{

    /**
     * @throws Throwable
     */
    public function CreateBook(array $validatedData) :Book
    {
        return DB::transaction(function () use ($validatedData) {
            $book = Book::query()->create($this->bookData($validatedData));
            $this->syncCategory($book, $validatedData['category_id']);
            $this->syncContributors(book: $book, author_id: $validatedData['author_id'], translator_id: $validatedData['translator_id'] ?? null);
            $this->setAttributes($book, $validatedData['attributes'] ?? []);
            return $book;
        });
    }

    /**
     * @throws Throwable
     */
    public function updateBook(Book $book, array $validatedData): Book
    {
      return  DB::transaction(function ()use ($book , $validatedData){
            $book->update($this->bookData($validatedData));
            $this->syncCategory($book,$validatedData['category_id']);
            $this->syncContributors($book, $validatedData['author_id'], $validatedData['translator_id']);
          $this->setAttributes($book, $validatedData['attributes'] ?? []);
            return $book;
        });
    }

    private function syncCategory(Book $book, array $category_id): void
    {
        $book->categories()->sync($category_id);
    }
    private function syncContributors(Book $book ,int $author_id, ?int $translator_id=null): void
    {
        $contributors = [
            $author_id => [
                'role' => ContributorRoleEnum::author->value,
            ],
        ];

        if ($translator_id !== null) {
            $contributors[$translator_id] = [
                'role' => ContributorRoleEnum::translator->value,
            ];
        }
        $book->contributors()->sync($contributors);
    }

    private function setAttributes(Book $book , array $attributes): void
    {
        $book->attributes()->detach();
        $attribute=collect($attributes);
        $attribute->each(function ($item) use ($book) {
            if (empty($item['attribute']) || !isset($item['value'])  || $item['value']==='') return;
            $attr = Attribute::query()->firstOrCreate(['name' => $item['attribute']]);
            $value = $attr->values()->firstOrCreate(['value' => $item['value']]);
            $book->attributes()->attach($attr->id,[
                'value_id'=>$value->id
            ]);
        });
    }

    private function bookData(array $validatedData): array
    {
      return array_intersect_key($validatedData, array_flip([
           'name',
           'ISBN',
           'description',
           'price',
           'stock',
           'publisher_id'
       ]));
    }
}
