{{--<reply :attributes="{{ $reply }}" inline-template v-cloak>--}}
    {{--<div id="reply-{{ $reply->id }}" class="card mt-4 mb-4">--}}
        {{--<div class="card-header">--}}
            {{--<div class="level">--}}
                {{--<h6 class="flex">--}}
                    {{--<a href="{{ route('profile.show', $reply->owner) }}">--}}
                        {{--{{ $reply->owner->name }}--}}
                    {{--</a> said {{ $reply->created_at->diffForHumans() }}--}}
                {{--</h6>--}}

                {{--@if(Auth::check())--}}
                    {{--<div class="">--}}
                        {{--<favorite :reply="{{ $reply }}"></favorite>--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="card-body">--}}
            {{--<div v-if="editing">--}}
                {{--<div class="form-group">--}}
                    {{--<textarea name="" id="" cols="10" rows="5" class="form-control" v-model="body"></textarea>--}}
                {{--</div>--}}
                {{--<button class="btn btn-sm btn-info" @click="update">Update</button>--}}
                {{--<button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>--}}
            {{--</div>--}}
            {{--<div v-else v-text="body"></div>--}}
        {{--</div>--}}

        {{--@can('update', $reply)--}}
            {{--<div class="card-footer level">--}}
                {{--<button class="btn btn-secondary btn-sm mr-3" @click="editing = true">Edit</button>--}}
                {{--<button class="btn btn-danger btn-sm mr-3" @click="destroy">Delete</button>--}}

                {{--<form method="POST" action="/replies/{{ $reply->id }}">--}}
                    {{--@csrf--}}
                    {{--@method('DELETE')--}}

                    {{--<button type="submit" class="btn btn-danger btn-sm">Delete</button>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--@endcan--}}
    {{--</div>--}}
{{--</reply>--}}