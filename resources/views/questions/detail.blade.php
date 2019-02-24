@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1>{{ $question->title }}</h1>
                            <div class="ml-auto">
                                <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">Back to all Questions</a>
                            </div>
                        </div>                        
                    </div>
                    <hr>
                    <div class="media">
                        @include ('shared._vote', [
                            'model' => $question,
                            'answers' => $question->answer
                        ])
                        <div class="media-body row">
                            <div class="col-md-9">
                                {!! $question->body !!}
                            </div>
                            <div class="col-md-3">
                                <div class="media">
                                    <a href="{{ $question->user->url }}" class="pr-2">
                                        <img style="width: 50px;" src="{{ $question->user->avatar }}">
                                    </a>
                                    <div class="media-body mt-1">
                                        <span class="text-muted">Answered {{ $question->created_date }}</span><br>
                                        <a href="{{ $question->user->url }}">{{ $question->user->name }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include ('answers._index', [
        'answers' => $question->answer,
        'answersCount' => $question->answers_count,
    ])
    @include ('answers._create')
</div>
@endsection