<?php

namespace App\Http\Controllers;

use App\Http\Requests\AskQuestionRequest;
use App\Repositories\Questions\Question;
use App\Repositories\Questions\QuestionRepository;
use Illuminate\Http\Request;

class QuestionsController extends Controller {
	/**
	 * QuestionsController constructor.
	 * @param QuestionRepository $question
	 */
	public function __construct(QuestionRepository $question) {
		$this->model = $question;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$this->authorize('question.view');
		$questions = $this->model->getAllPaginate(5);
		return view('questions.index', compact('questions'))->render();

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$this->authorize('question.view');
		$question = $this->model->getById($id);
		return view('questions.detail', compact('question'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$this->authorize('question.create');
		$question = new Question();
		return view('questions.create', compact('question'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(AskQuestionRequest $request) {
		$this->authorize('question.create');
		$data = $request->except('slug');
		$data['slug'] = str_slug($request->title);
		$request->user()->questions()->create($data);
		return redirect()->route('questions.index')->with('success', "Your question has been submitted");
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Question $question) {
		$this->authorize('question.update');
		return view("questions.edit", compact('question'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(AskQuestionRequest $request, $id) {
		$this->authorize('question.update');
		$this->model->update($id, $request->all());
		return redirect('/questions')->with('success', "Your question has been updated.");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$this->authorize('question.delete');
		$this->model->delete($id);
		return redirect('/questions')->with('success', "Your question has been deleted.");
	}
}
