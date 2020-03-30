@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2">
                <div class="card-header">
                    <h1>
                        {{ $profileUser->name }}
                        {{--<small>Since {{ $profileUser->created_at->diffForHumans() }}</small>--}}
                    </h1>
                </div>

                @forelse($activities as $date => $activity)
                    <h3 class="card-header mt-4">{{ $date }}</h3>

                    @foreach($activity as $record)
                        @if(view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection