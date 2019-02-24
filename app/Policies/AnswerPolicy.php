<?php

namespace App\Policies;

use App\User;
use App\Repositories\Answers\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the Answer.
     *
     * @param  \App\User  $user
     * @param  \App\Repositories\Answers\Answer  $answer
     * @return mixed
     */
    public function view(User $user, Answer $answer = null)
    {
        return $user->hasAccess(['answer.view']);
    }

    /**
     * Determine whether the user can create answer.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['answer.create']);
    }

    /**
     * Determine whether the user can update the answer.
     *
     * @param  \App\User  $user
     * @param  \App\Repositories\Answers\Answer  $answer
     * @return mixed
     */
    public function update(User $user, Answer $answer)
    {
        return $user->hasAccess(['answer.update']);
    }

    /**
     * Determine whether the user can delete the answer.
     *
     * @param  \App\User  $user
     * @param  \App\Repositories\Answers\Answer  $answer
     * @return mixed
     */
    public function delete(User $user, Answer $answer)
    {
        return $user->hasAccess(['answer.delete']);
    }
}
