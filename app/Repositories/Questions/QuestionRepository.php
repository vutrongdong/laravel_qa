<?php

namespace App\Repositories\Questions;

use App\Repositories\BaseRepository;

class QuestionRepository extends BaseRepository
{
    /**
     * Question model.
     * @var Model
     */
    protected $model;

    /**
     * QuestionRepository constructor.
     * @param Question $question
     */
    public function __construct(Question $question)
    {
        $this->model = $question;
    }


    public function getUrlAttribute()

    {
        return route("questions.show", $this->slug);
    }


    public function getCreatedDateAttribute()

    {
        return $this->created_at->diffForHumans();
    }
    
}
