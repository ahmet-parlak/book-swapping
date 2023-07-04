@extends('template')
@section('title')
    User | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/user.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')

    <div class="container mt-5">
        <div class="row mt-1 align-items-center">
            <div class="col-1 offset-3 text-end">
                <div class="user-pp">
                    <img src="{{ $user->user_photo }}" alt="">
                </div>
            </div>
            <div class="col-2 text-center offset-1">
                <div class="user ">
                    {{ $user->first_name }} {{ mb_substr($user->last_name, 0, 1,"utf-8") }}.
                    <div class="city ">
                        <span class="fw-bold">Şehir:</span> <span>{{ $user->city }}</span>
                    </div>
                    <div class="city ">
                        <span class="fw-bold">Başarılı Takas:</span> <span>{{ $successTrade }}</span>
                    </div>
                </div>
            </div>
            <div class="col-2 text-center">
                <div class="in-swap position-relative pb-2">
                    <x-icons.bookbox /> <span class="ms-1 position-absolute top-0 start-1 translate-middle rounded-circle ">
                        {{ $books->total() }} </span>
                </div>
            </div>
            <div class="col-2 pb-4">
                <div class="form-button mt-4 text-start">
                    <a href="{{ route('trade') }}?user={{ $user->user_id }}" type="submit"
                        class="btn btn-primary link-btn" title="Takas Teklifi">TEKLİF</a>
                </div>
                <div class="form-button mt-2 text-start envelope-icon">
                    {{-- <a href="{{route('toUser',$user->user_id)}}" type="submit" class="btn btn-primary link-btn"> MESAJ </a> --}}
                    <a href="{{ route('toUser', $user->user_id) }}" type="submit" title="Mesaj Gönder">
                        <x-icons.envelope />
                    </a>
                </div>
            </div>
        </div>

        {{-- Books --}}
        @foreach ($books as $book)
            <div class="col-md-7 my-2 offset-3  border-top border-2 pt-4 ps-2">
                <div class="book">
                    <div class="row ">
                        <div class="col-md-2">
                            <div class="book-img">
                                <a href="{{ route('book') }}?book={{ $book->book_id }}"><img class="book-img"
                                        src="{{ $book->image }}" alt="book"></a>
                            </div>
                        </div>
                        <div class="col-md-8 align-middle">
                            <div class="book-name"><a
                                    href="{{ route('book') }}?book={{ $book->book_id }}">{{ $book->book_name }}</a>
                            </div>
                            <div class="author">Yazar: <span><a
                                        href="{{ route('store') }}?search={{ $book->author }}">{{ $book->author }}</a></span>
                            </div>
                            <div class="publisher">Yayınevi: <span><a
                                        href="{{ route('store') }}?search={{ $book->publisher }}">{{ $book->publisher }}</a></span>
                            </div>
                            <div class="isbn">ISBN: <span style="user-select:text">{{ $book->isbn }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-5 pt-5">
        {{ $books->links() }}
    </div>


   {{--  @if ($books->count() > 3)
        <style>
            .footer {
                position: static;
                margin-top: 100px;
            }
        </style>
    @endif --}}
@endsection
