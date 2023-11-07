@extends('layouts.app')

@section('title', $material->title)

@section('content')
    <div>
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
