<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\GeneralQueryRepositoryInterface',
            'App\Repositories\MySQLGeneralQueryRepository'
        );

        $this->app->bind(
            'App\Repositories\QuizResultsRepositoryInterface',
            'App\Repositories\MySQLQuizResultsRepository'
        );

        $this->app->bind(
            'App\Repositories\TriviaClientRepositoryInterface',
            'App\Repositories\MySQLTriviaClientRepository'
        );

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
