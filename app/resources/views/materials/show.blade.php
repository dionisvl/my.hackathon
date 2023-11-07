@extends('layouts.app')

@section('title', $material->title)

@section('content')
    <div>
        <img class="w-full rounded-xl my-8"
             src=""
             alt="{{ $material->title }}"/>

        <div class="prose
            min-w-full
            prose-img:rounded-xl
            prose-invert"
        >
            <x-title>{{ $material->title }}</x-title>

            <div class="mt-4">
                {!! $material->content !!}
            </div>
        </div>
    </div>
@endsection
