@extends('layouts.app')
<style type="text/css">
    body {
        background-color: #fbfbfb;
    }

    .img {
        width: 100%;
        height: 293px;
        object-fit: cover;
    }

    .fa-2x {
        font-size: 1.5em !important;
    }

    .container {
        max-width: 1024px;
    }

    .btn {
        background: transparent !important;
    }
    .btn-custom {
        color: rgb(0, 0, 0);
    }

    .btn-custom:hover,
    .btn-custom:focus,
    .btn-custom:active,
    .btn-custom.active,
    .open > .dropdown-toggle.btn-custom {
        color: rgb(223, 69, 69);
    }

    textarea {
        resize: none;
    }

    .btn:focus {
        box-shadow: none;
    }

    .border-linear {
        background-color: white;
        padding: 5px;
        border: 2px solid;
        border: 2px solid #e4524e;
        border-radius: 50%;
    }

    /* Double Click Like effect */
    .js-post {
        position: relative;
        cursor: pointer;
        overflow: hidden;
    }

    .js-post i {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        color: red;
        z-index: 99;
        font-size: 40px;
        opacity: 0;
        -webkit-transition: opacity 1s;
        -moz-transition: opacity 1s;
        transition: opacity 1s;
    }

    .js-post i.fade {
        opacity: 1;
    }
</style>
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            {{-- Main section --}}
            <main class="main col-md-8 px-2 py-3">

                @forelse ($posts as $post)

                    @php
                        $state=false;
                    @endphp

                    <div class="card mx-auto custom-card mb-5" id="prova">
                        <!-- Card Header -->
                        <div class="card-header d-flex justify-content-between align-items-center bg-white pl-3 pr-1 py-2">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('profile.index', $post->user->username)}}" style="width: 32px; height: 32px;">
                                    <img src="{{ asset($post->user->profile->getProfileImage()) }}" class="rounded-circle w-100">
                                </a>
                                <a href="{{ route('profile.index', $post->user->username)}}" class="my-0 ml-3 text-dark text-decoration-none">
                                    {{ $post->user->name }}
                                </a>
                            </div>
                            <div class="card-dots">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-link text-muted" data-toggle="modal" data-target="#post{{$post->id}}">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>

                                <!-- Dots Modal -->
                                <div class="modal fade" id="post{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                    <div class="modal-content">
                                        <ul class="list-group">
                                            @can('delete', $post)
                                                <form action="{{url()->action('PostController@destroy', $post->id)}}" method="POST">
                                                    @csrf
                                                    @method("DELETE")
                                                    <li class="btn btn-danger list-group-item">
                                                        <button class="btn" type="submit">Delete</button>
                                                        </li>
                                                </form>
                                            @endcan
                                            <a href="{{ route('post.show', $post->id) }}"><li class="btn list-group-item">Go to post</li></a>
                                            <a href="#"><li class="btn list-group-item" data-dismiss="modal">Cancel</li></a>
                                        </ul>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Image -->
                        <div class="js-post" ondblclick="showLike(this, 'like_{{ $post->id }}')">
                            <i class="fa fa-heart"></i>
                            <img class="card-img" src="{{ asset("storage/$post->image") }}" alt="post image" style="max-height: 767px">
                        </div>

                        <!-- Card Body -->
                        <div class="card-body px-3 py-2">

                            <div class="d-flex flex-row">
                                <form method="POST" action="{{url()->action('LikeController@update', ['like'=>$post->id])}}">
                                    @csrf
                                    @if (true)
                                        <input id="inputid" name="update" type="hidden" value="1">
                                    @else
                                        <input id="inputid" name="update" type="hidden" value="0">
                                    @endif

                                    @if($post->like->isEmpty())
                                        <button type="submit" class="btn pl-0">
                                            <i class="far fa-heart fa-2x"></i>
                                        </button>
                                    @else

                                        @foreach($post->like as $likes)

                                            @if($likes->user_id==Auth::User()->id && $likes->State==true)
                                                @php
                                                    $state=true;
                                                @endphp
                                            @endif

                                        @endforeach

                                        @if($state)
                                            <button type="submit" class="btn pl-0">
                                                <i class="fas fa-heart fa-2x" style="color:red"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn pl-0">
                                                <i class="far fa-heart fa-2x"></i>
                                            </button>
                                        @endif

                                    @endif

                                    <a href="{{ route('post.show', $post->id) }}" class="btn pl-0">
                                        <i class="far fa-comment fa-2x"></i>
                                    </a>

                                    <!-- Share Button trigger modal -->
                                    <button type="button" class="btn pl-0 pt-1" data-toggle="modal" data-target="#sharebtn{{$post->id}}">
                                        <i class="far fa-paper-plane fa-2x"></i>
                                    </button>

                                    <!-- Share Modal -->
                                    <div class="modal fade" id="sharebtn{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content">
                                            <ul class="list-group">
                                                <li class="list-group-item" style="position: absolute; left: -1000px; top: -1000px">
                                                    <input type="text" value="http://localhost:8000/p/{{ $post->id }}" id="copy_{{ $post->id }}" />
                                                </li>
                                                <li class="btn list-group-item" data-dismiss="modal" onclick="copyToClipboard('copy_{{ $post->id }}')">Copy Link</li>
                                                <li class="btn list-group-item" data-dismiss="modal">Cancel</li>
                                            </ul>
                                        </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="flex-row">

                                <!-- Likes -->
                                @if (count($post->like->where('State',true)) > 0)
                                    <h6 class="card-title">
                                        <strong>{{ count($post->like->where('State',true)) }} likes</strong>
                                    </h6>
                                @endif

                                {{-- Post Caption --}}
                                <p class="card-text mb-1">
                                    <a href="{{ route('profile.index', $post->user->username) }}" class="my-0 text-dark text-decoration-none">
                                        <strong>{{ $post->user->name }}</strong>
                                    </a>
                                    {{ $post->caption }}
                                </p>

                                <!-- Comment -->
                                <div class="comments">
                                    @if (count($post->comments) > 0)
                                        <a href="{{ route('post.show', $post->id) }}" class="text-muted">View all {{count($post->comments)}} comments</a>
                                    @endif
                                    @foreach ($post->comments->sortByDesc("created_at")->take(2) as $comment)
                                        <p class="mb-1"><strong>{{ $comment->user->name }}</strong>  {{ $comment->body }}</p>
                                    @endforeach
                                </div>

                                <!-- Created At  -->
                                <p class="card-text text-muted">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer p-0">
                            <!-- Add Comment -->
                            <form action="{{ action('CommentController@store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-0  text-muted">
                                    <div class="input-group is-invalid">
                                        <input type="hidden" name="post_id" value="{{$post->id}}">
                                        <textarea class="form-control" id="body" name='body' rows="1" cols="1" placeholder="Add a comment..."></textarea>
                                        <div class="input-group-append">
                                            <button class="btn btn-md btn-outline-info" type="submit">Post</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>

                @empty

                    <div class="d-flex justify-content-center p-3 py-5 border bg-white">
                        <div class="card border-0 text-center">
                            <img src="{{asset('images/image_post.svg')}}" class="card-img-top" alt="..." style="max-width: 330px">
                            <div class="card-body ">
                                <h3>No Post found</h3>
                                <p class="card-text text-muted">We couldn't find any post, Try to follow someone</p>
                            </div>
                        </div>
                    </div>

                @endforelse

            </main>

            {{-- Aside Section --}}
            <aside class="aside col-md-4 py-3">
                <div class="position-fixed">

                    <!-- User Info -->
                    <div class="d-flex align-items-center mb-3">
                        <a href="{{ route('profile.index', Auth::user()->username)}}" style="width: 56px; height: 56px;">
                            <img src="{{ asset(Auth::user()->profile->getProfileImage()) }}" class="rounded-circle w-100">
                        </a>
                        <div class='d-flex flex-column pl-3'>
                            <a href="{{ route('profile.index', Auth::user()->username)}}" class='h6 m-0 text-dark text-decoration-none' >
                                <strong>{{ auth()->user()->username }}</strong>
                            </a>
                            <small class="text-muted ">{{ auth()->user()->name }}</small>
                        </div>
                    </div>

                    <!-- Suggestions -->
                    <div class='mb-4' style="width: 300px">
                        <h6 class='text-secondary'>Suggestions For You</h5>

                        <!-- Suggestion Profiles-->
                        @foreach ($sugg_users as $sugg_user)
                            @if ($loop->iteration == 6)
                                @break
                            @endif
                            <div class='suggestions py-2'>
                                <div class="d-flex align-items-center ">
                                    <a href="{{ route('profile.index', $sugg_user->username)}}" style="width: 32px; height: 32px;">
                                        <img src="{{ asset($sugg_user->profile->getProfileImage()) }}" class="rounded-circle w-100">
                                    </a>
                                    <div class='d-flex flex-column pl-3'>
                                        <a href="{{ route('profile.index', $sugg_user->username)}}" class='h6 m-0 text-dark text-decoration-none' >
                                            <strong>{{ $sugg_user->name}}</strong>
                                        </a>
                                        <small class="text-muted">New to Instagram </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </aside>

        </div>
    </div>

@endsection


@section('exscript')
    <script>
        function copyToClipboard(id) {
            var copyText = document.getElementById(id);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
        }

        function showLike(e, id) {
            console.log("Like: ", id);
            var heart = e.firstChild;
            heart.classList.add('fade');
            setTimeout(() => {
                heart.classList.remove('fade');
            }, 2000);
        }

    </script>
@endsection