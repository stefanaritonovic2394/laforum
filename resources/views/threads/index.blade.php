@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse($threads as $thread)
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="level">
                                <h4 class="flex">
                                    <a href="{{ $thread->path() }}">
                                        @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                            <strong>{{ $thread->title }}</strong>
                                        @else
                                            {{ $thread->title }}
                                        @endif
                                    </a>
                                </h4>

                                <a href="{{ $thread->path() }}">
                                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            {{--@if (session('status'))--}}
                                {{--<div class="alert alert-success" role="alert">--}}
                                    {{--{{ session('status') }}--}}
                                {{--</div>--}}
                            {{--@endif--}}

                            <div class="">{{ $thread->body }}</div>
                        </div>
                    </div>
                @empty
                    <p>No results at this time.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
