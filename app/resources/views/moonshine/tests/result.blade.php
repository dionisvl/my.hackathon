@extends('layouts.app')

@section('content')
    <div>
        <div class="prose
            min-w-full
            prose-img:rounded-xl
            prose-invert text-black"
        >
            <h1>Результаты прохождения теста: </h1>
            <b>Тест: {{ $userTest->test->title }}</b>
            <div>Оценка: <b style="color:red">{{ $userTest->result }}</b></div>
            <div>Дата прохождения: {{ $userTest->completed_at }}</div>
            <div>Сотрудник: {{$userTest->user->name}}</div>
        </div>
    </div>

@endsection
