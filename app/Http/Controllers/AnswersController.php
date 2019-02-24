<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Answers\Answer;
use App\Repositories\Questions\Question;
use App\Repositories\Answers\AnswerRepository;

class AnswersController extends Controller
{

    /**
     * AnswersController constructor.
     * @param AnswersRepository $answer
     */
    public function __construct(AnswerRepository $answer) {
        $this->model = $answer;
    }
    /**
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('answer.view');
        $answers = $this->model->getAll();
        return view('Answers.index', compact('answers'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, Request $request)
    {
        $this->authorize('answer.create');
        $answer = $question->answer()->create($request->validate([
            'body' => 'required'
        ]) + ['user_id' => \Auth::id()]);

        if ($request->expectsJson())
        {
            return response()->json([
                'message' => "Your answer has been submitted successfully",
                'answer' => $answer->load('user')
            ]);
        }

        return back()->with('success', "Your answer has been submitted successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question, Answer $answer)
    {
        $this->authorize('answer.update');
        return view('answers.edit', compact('question', 'answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('answer.update');
        $answer->update($request->validate([
            'body' => 'required',
        ]));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your answer has been updated',
                'body_html' => $answer->body_html
            ]);
        }
        return redirect()->route('questions.show', $question->id)->with('success', 'Your answer has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question, Answer $answer)
    {
        $this->authorize('answer.delete');
        $this->authorize('delete', $answer);
        $answer->delete();
        if (request()->expectsJson())
        {
            return response()->json([
                'message' => "Your answer has been removed"
            ]);
        }
        
        return back()->with('success', "Your answer has been deleted.");
    }
}
