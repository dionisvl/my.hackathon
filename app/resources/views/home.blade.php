@extends('layouts.app')

@section('content')
    <div x-data="home">
        <div  class="tasks grid gap-4 grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-14 xl:gap-y-20 mt-12 md:mt-20">

            @include('shared.card', [
                'href' => 'google.com',
                'title' => 'YouTube',
                'cover' => 'images/projects/youtube.jpg'
            ])
        </div>
    </div>

    <script aria-hidden="true">
      document.addEventListener('alpine:init', () => {Alpine.data('home', () => ({ modal: false }))
        Alpine.bind('backdrop', () => ({ ['x-show']() { return this.modal }, ['x-on:keydown.escape.prevent.stop']() { this.modal = false }, ['role']: 'dialog', ['aria-modal']: 'true', ['x-id']() { return ['modal-title'] }, [':aria-labelledby']() { return this.$id('modal-title') }, }))
      })</script>
@endsection
