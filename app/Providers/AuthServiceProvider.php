<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Repositories\Questions\Question::class => \App\Policies\QuestionPolicy::class,
        \App\Repositories\Answers\Answer::class => \App\Policies\AnswerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    private function registerGates() {
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        Gate::resource('question', \App\Policies\QuestionPolicy::class);
        Gate::resource('answer', \App\Policies\QuestionPolicy::class);
    }
}
