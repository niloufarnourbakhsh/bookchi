<?php

namespace Modules\Book\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Book\Models\Attribute;
use Modules\Book\Models\Category;
use Modules\Book\Models\Contributor;
use Modules\Book\Models\Publisher;


class BookViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function boot(): void
    {
        View::composer([
            'book::admin.book.edit',
            'book::admin.book.create'
        ], function ($view) {
            $view->with([
                'contributors' => Contributor::all(),
                'categories' => Category::all(),
                'publishers' => Publisher::all(),
                'attributes' => Attribute::with('values')->get(),
            ]);
        });
    }

    public function register(): void
    {
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
