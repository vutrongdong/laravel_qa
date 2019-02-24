<?php

namespace App\Policies;

use App\User;
use App\Repositories\Questions\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the Question.
     *
     * @param  \App\User  $user
     * @param  \App\Repositories\Questions\Question  $question
     * @return mixed
     */
    public function view(User $user, Question $question = null)
    {
        return $user->hasAccess(['question.view']);
    }

    /**
     * Determine whether the user can create Question.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['question.create']);
    }

    /**
     * Determine whether the user can update the Question.
     *
     * @param  \App\User  $user
     * @param  \App\Repositories\Questions\Question  $question
     * @return mixed
     */
    public function update(User $user, Question $question)
    {
        return $user->hasAccess(['question.update']);
    }

    /**
     * Determine whether the user can delete the Question.
     *
     * @param  \App\User  $user
     * @param  \App\Repositories\Questions\Question  $question
     * @return mixed
     */
    public function delete(User $user, Question $question)
    {
        return $user->hasAccess(['question.delete']);
    }
}
