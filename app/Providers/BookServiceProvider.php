<?php

namespace App\Providers;

use App\Book;
use Illuminate\Support\ServiceProvider;
use App\Services\BookService;

class BookServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BookService::class, function ($app) {
            return new BookService(new Book());
        });
    }

    public function provides()
    {
        return [BookService::class];
    }
}
