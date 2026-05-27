<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Pagination\Paginator;

use App\Events\QuestionAnswered;
use App\Listeners\NotifyQuestionAuthorOnAnswer;
use App\Events\AnswerAccepted;
use App\Listeners\NotifyAnswerAuthorOnAccepted;
use App\Events\ThreadReplied;
use App\Listeners\NotifyThreadAuthorOnReply;
use App\Events\ReputationChanged;
use App\Listeners\AwardBadgesOnReputationChange;
use App\Listeners\UpdateUserReputation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        Event::listen(QuestionAnswered::class, NotifyQuestionAuthorOnAnswer::class);
        Event::listen(AnswerAccepted::class, NotifyAnswerAuthorOnAccepted::class);
        Event::listen(ThreadReplied::class, NotifyThreadAuthorOnReply::class);
        Event::listen(ReputationChanged::class, AwardBadgesOnReputationChange::class);
        Event::listen(ReputationChanged::class, UpdateUserReputation::class);
    }
}