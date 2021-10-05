@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row ">
        <div class="col-3 p-5">
            <img src="{{ asset($user->profile->getProfileImage()) }}" class="rounded-circle w-100">
        </div>

        <div class="col-9 pt-5">
            <div class="d-flex align-items-center">
                <h1>{{ $user->username }}</h1>

                @can('update', $user->profile)
                    <a class="btn btn-outline-secondary ml-3 btn-sm" href="{{ route('profile.edit', $user->username)}}" role="button">
                        Edit Profile
                    </a>
                @else
                    <form method="post" action="{{route('follow.store', $user->username) }}">
                        @csrf

                        @if ($follows)
                            <button class="btn btn-primary ml-3 btn-sm" type="submit"> Unfollow</button>
                        @else
                            <button class="btn btn-primary ml-3 btn-sm" type="submit"> Follow</button>
                        @endif
                    </form>
                @endcan

            </div>
            <div class="d-flex">
                <div class="pr-5"><strong> {{ $postCount }} </strong> posts</div>
                <div class="pr-5"><strong> {{ $followersCount }} </strong> followers</div>
                <div class="pr-5"><strong> {{ $followingCount }} </strong> following</div>
            </div>
            <div class="pt-4 font-weight-bold ">{{ $user->name }}</div>
            <div>
                {!! nl2br(e($user->profile->bio)) !!}
            </div>
            <div class="font-weight-bold">
                <a href="{{ $user->profile->website }}" target="_blanc">
                    {{ $user->profile->website }}
                </a>
            </div>

        </div>
    </div>

    <div class="row pt-4 border-top">

        @forelse ($user->posts as $post)
            <div class="col-4 col-md-4 mb-4 align-self-stretch">
                <a href="{{ route('post.show', $post->id) }}">
                    <img class="img border" height="300" src="{{ asset("storage/$post->image") }}"
                        style="width: 100%;
                        height: 293px;
                        object-fit: cover;">
                </a>
            </div>
        @empty
            <div class="col-12 d-flex justify-content-center text-muted">
                <div class="card border-0 text-center bg-transparent" >
                    <h1>No Posts Yet</h1>
                    <img src="{{asset('images/image_post.svg')}}" class="card-img-top" alt="...">
                    <div class="card-body ">

                    </div>
                </div>
            </div>
        @endforelse

    </div>
</div>
@endsection