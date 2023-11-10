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
                        Прогресс онбординга пользователя: <br>{{ $user->id }}. {{ $user->name }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Курс: {{ $course->title }}</h5>
                        @php
                            $allMaterialsViewed = $course->materials->every(function ($material) use ($user) {
                                return $user->materials->where('material_id', $material->id)->whereNotNull('viewed_at');
                            });
                            $allTestsPassed = $course->tests->every(function ($test) use ($user) {
                                $userTest = $user->tests->where('test_id', $test->id)->first();
                                return $userTest && $userTest->result >= \App\Models\UserTest::PASS_THRESHOLD;
                            });
                            $courseCompleted = $allMaterialsViewed && $allTestsPassed;
                        @endphp
                        <p class="card-text badge badge-primary">Дата начала курса: {{ $course->start_at }}</p>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Материалы:
                            </li>
                            @foreach ($course->materials as $material)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $material->title }}
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
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Тесты:
                            </li>
                            @foreach ($course->tests as $test)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Тест: {{ $test->title }}</span>
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
                        @if ($courseCompleted)
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
