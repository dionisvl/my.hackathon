@extends('layouts.app')

@section('title', $material->title)

@section('content')
    <div>
        <div class="prose
            min-w-full
            prose-img:rounded-xl
            prose-invert"
        >
            <h5>Изучите материал: </h5>
            <x-title>{{ $material->title }}</x-title>

            <div class="text-black mt-4">
                {!! $material->content !!}
                <hr>

                @if($material->files)
                    @php
                        // Предполагаем, что $jsonFiles — это JSON-строка с путями материалов.
                        // Декодируем JSON-строку в массив для итерации.
                        $jsonFiles = $material->files;
                        $files = json_decode($jsonFiles);
                    @endphp

                    @if($files)
                        <ul>
                            @foreach($files as $file)
                                @php
                                    // Определение расширения файла
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                @endphp
                                <li>
                                    @if(in_array($extension, ['doc', 'docx']))
                                        {{-- Если файл является документом Word, используем ссылку для его просмотра --}}
                                        <a href="https://docs.google.com/gview?url={{ urlencode(Storage::url($file)) }}&embedded=true"
                                           target="_blank">{{ $file }}</a>
                                    @else
                                        {{-- Для всех остальных типов файлов просто создаём ссылку для скачивания --}}
                                        <a href="{{ Storage::url($file) }}" download>{{ $file }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <div>Файлов к данному материалу нет.</div>
                @endif

            </div>

            <blockquote class="text-black">После прочтения данного материала можете нажать кнопку "назад" и выбрать
                следующий.
            </blockquote>
        </div>
    </div>
@endsection
