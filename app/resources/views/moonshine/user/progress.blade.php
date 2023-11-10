@extends('layouts.app')

@section('content')

    <div>
        <div class="prose
            min-w-full
            prose-img:rounded-xl
            prose-invert"
        >
            <div class="container mt-4">
                <div class="card">

                    <div class="card-header">
                        Курс: {{ $course->id }}. {{ $course->title }}<br>
                        Сотрудник: {{ $user->id }}. {{ $user->name }}
                    </div>
                    <div class="card-body">
                        <h2 class="card-title">Прогресс онбординга пользователя</h2>
                        <p class="card-text badge badge-primary">Дата начала курса: {{ $course->start_at }}</p>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center  btn-outline-success">
                                Материалы:
                            </li>
                            @foreach ($course->materials as $material)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ route('moonshine.materials.show', ['material' => $material->id]) }}">
                                        {{ $material->title }}
                                    </a>
                                    @php
                                        $userMaterial = $user->materials->where('material_id', $material->id)->first();
                                    @endphp
                                    <span
                                        class="badge {{ $userMaterial && $userMaterial->viewed_at ? 'bg-success' : 'bg-danger' }}">
                {{ $userMaterial && $userMaterial->viewed_at ? 'Изучен' : 'Не изучен' }}
                                        @if($userMaterial && $userMaterial->viewed_at)
                                            <small>({{ $userMaterial->viewed_at }})</small>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center btn-outline-info">
                                Тесты:
                            </li>
                            @foreach ($course->tests as $test)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Тест: <a href="{{ route('moonshine.tests.start', ['test' => $test->id]) }}">
                                        {{ $test->title }}
                                    </a>
                                    </span>
                                    @php
                                        $userTest = $user->tests->where('test_id', $test->id)->first();
                                        $isPassed = $userTest && $userTest->result >= \App\Models\UserTest::PASS_THRESHOLD;
                                    @endphp
                                    <div>
                                        <span
                                            class="me-2">Результат: {{ $userTest ? $userTest->result . '%' : 'Нет результатов' }}
                                        </span>
                                        <span class="badge {{ $isPassed ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $isPassed ? 'Пройден' : 'Не пройден' }}
                                            @if($userTest)
                                                <small>({{ $userTest->completed_at }})</small>
                                            @endif
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <p class="card-text mt-3 card-text badge badge-primary">Окончание
                            курса: {{ $course->end_at }}</p>
                        @if ($user->isCourseCompleted())
                            <div class="alert alert-success" role="alert">
                                Курс успешно пройден!
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                Курс онбординга не пройден
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
