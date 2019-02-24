<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Repositories\Questions\Question;
use App\Repositories\Answers\Answer;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }


    public function getUrlAttribute()
    {
        return '#';
    }


    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    
    public function favorites()
    {
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps(); //, 'author_id', 'question_id');
    }
    
        public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'votable');
    }

    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'votable');
    }

    public function voteQuestion(Question $question, $vote)
    {
        $voteQuestions = $this->voteQuestions();
        
        return $this->_vote($voteQuestions, $question, $vote);
    }

    public function voteAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->voteAnswers();
        
        return $this->_vote($voteAnswers, $answer, $vote);
    }   
    
    private function _vote($relationship, $model, $vote)
    {
        if ($relationship->where('votable_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }
        else {
            $relationship->attach($model, ['vote' => $vote]);
        }
        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');
        
        $model->votes_count = $upVotes + $downVotes;
        $model->save();
        return $model->votes_count;
    }

    public function getAvatarAttribute()
    {
        $email = $this->email;        
        $size = 32;
        return "https://lh3.googleusercontent.com/vRxAB8OZP0CPgFRsZhhEJWp_7-ueB9-qr6cES3QlJIJws3kc8SUW4Ilt5kMG2K_2L6oOQXfGeml_lvBJh-3bRTzDJHfaaJsTmi3O63ghUngZnxWYlvJ5U06Caj5NSAfORfk2LM_HpYPs9GVMoZ1N4pqNAARPFbvLRjP55PRAXA5DpD8YOtW08vcwjv43Xh259XlJBcRNPGnVn9pZB0gy0weOk9c5rFUXOUnziJ6KNK1VZHtf-kUz3birSz07d1rYbJhyYzZV2FBuRMUBVFPGld9o7hwjifhLYdfTfM_QWgY-DNA-linSvwhCtCBPG8xCPi0wVISyNRah33iv-5wr4xpLJ5XrpIeN9wTa3TaGGW9qZ78_eTWnvnXSdbXJ64i4pMal7cp0K111BWeKtIzgUVRRB1PP_t-CNR5NKobsiMyK6x3YCeAcHmWJpt1A4j-d2L8RWllbaInk3W3dnOf1i6eugXrnmi5i8uOyfWVdYBLXvTPZKl2DnDWk83Z7qlO8CtiZcr6v4p_iC6AK-qoZeN7Pobch1vtvgShnS4NiuBRxlpmQTZEVlNEGpBVeJVQ72JdhldgEf8S9oExpIAoP4P6EYCktjrh3puULIWr1jWeI69avUS-HgsXycOBv8D8cW4l8s1cLXEGZ6NlDd7Jh0iSFYOo_qPQ=w771-h969-no";
    }

    public function roles()
    {
        return $this->belongsToMany('App\Repositories\Roles\Role', 'role_users');
    }


        /**
     * [hasAccess description]
     * @param  array   $permissions
     * @return boolean
     */
    public function hasAccess(array $permissions) : bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * [inRole description]
     * @param  string $slug
     * @return boolean
     */
    public function inRole(string $slug) : bool
    {
        return $this->roles()->where('slug', $slug)->count() == 1;
    }

    /**
     * [isSuperAdmin description]
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return $this->hasAccess(['admin.super-admin']);
    }
}
