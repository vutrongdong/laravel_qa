<?php

namespace App\Repositories\Answers;

use App\Repositories\BaseRepository;

class AnswerRepository extends BaseRepository
{
    /**
     * Answer model.
     * @var Model
     */
    protected $model;

    /**
     * AnswerRepository constructor.
     * @param Answer $answer
     */
    public function __construct(Answer $answer)
    {
        $this->model = $answer;
    }
    
}
