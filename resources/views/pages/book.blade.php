@extends('template')
@section('title')
    {{ $book->book_name }} | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/book.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <div class="container mt-5">
        <div class="row offset-2 ">
            <div class="col-2 text-end pb-2">
                <img class="book-img" src="{{ $book->image }}" alt="book">
            </div>
            <div class="col-6 ">
                <div class="book-name mb-1 border-bottom">
                    <h2>{{ $book->book_name }}</h2>
                </div>
                <div class="author">Yazar: <span><a
                            href="{{ route('store') }}?search={{ $book->author }}">{{ $book->author }}</a></span>
                </div>
                <div class="year">Yıl: <span>{{ $book->publication_year }}</span></div>
                <div class="publisher">Yayınevi: <span><a
                            href="{{ route('store') }}?search={{ $book->publisher }}">{{ $book->publisher }}</a></span>
                </div>
                <div class="isbn">ISBN: <span style="user-select:text">{{ $book->isbn }}</span></div>
                <div class="d-inline-flex">
                    <div class="in-swap position-relative mt-3">
                        <x-icons.bookbox /> <span
                            class="ms-1 position-absolute top-0 start-1 translate-middle">{{ $book->intrade }}</span>
                    </div>
                    @if ($favorite)
                        <div id="{{ $book->book_id }}-star" class="in-favorites pt-3" data-bs-toggle="tooltip"
                            data-bs-placement="left" title="Favorilerde">
                            <x-icons.heart-icon />
                        </div>
                    @else
                        <div class="add-list mt-3 bookmark-heart-icon add-favorites-icon" book="{{ $book->book_id }}"
                            data-bs-toggle="tooltip" data-bs-placement="left" title="Favorilere Ekle">
                            <x-icons.bookmark-heart />
                        </div>
                        <div id="{{ $book->book_id }}-star" class="in-favorites d-none pt-3" data-bs-toggle="tooltip"
                            data-bs-placement="left" title="Favorilerde">
                            <x-icons.heart-icon />
                        </div>
                    @endif
                </div>
            </div>



            @if ($bookshelf)
                <div class="col-2 pt-4" data-bs-toggle="tooltip" data-bs-placement="left" title="Kitaplıkta">
                    <div class="in-bookshelf-icon">
                        <x-icons.bookshelf />
                    </div>
                </div>
            @else
                <div class="col-2 add-bookshelf-icon" book="{{ $book->book_id }}" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Kitaplığa Ekle">
                    <x-icons.add-to-bookshelf />
                </div>
                <div class="col-2 pt-5" data-bs-toggle="tooltip" data-bs-placement="left" title="Kitaplıkta">
                    <div class="in-bookshelf-icon d-none" id="{{ $book->book_id }}-inshelf">
                        <x-icons.bookshelf />
                    </div>
                </div>
            @endif

        </div>

        {{-- Swaps --}}
        <div class="row offset-2">
            <div class="col-10 mt-1 border-top border-2"></div>
        </div>
        @if ($users)
            @foreach ($users as $user)
                <div class="row mt-2 align-items-center">
                    <div class="col-1 offset-3 text-end">
                        <div class="user-pp">
                            <a href="user?user={{ $user->user_id }}"><img src="{{ $user->user_photo }}" alt=""></a>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="user">
                            <a href="user?user={{ $user->user_id }}">{{ $user->first_name }}
                                {{ strtoupper(mb_substr($user->last_name, 0, 1,"utf-8")) }}.</a>
                        </div>
                    </div>
                    <div class="col-2 text-start">
                        <div class="city">
                            {{ $user->city }}
                        </div>
                    </div>
                    @if ($user->user_id != Auth::user()->user_id)
                        <div class="col-2 pb-4">
                            <div class="form-button mt-4 text-start">
                                <a href="{{route('trade')}}?user={{$user->user_id}}" type="submit" class="btn btn-primary">TEKLİF</a>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert" style="color:#cc0621 ">
                <p class="text-dark mb-0">Bu kitabı takas edebileceğiniz bir kullanıcı yok.</p>
            </div>
        @endif


    </div>


    <script>
        let addFavoritesUrl = '{{ route('addFavorites') }}',
            addBookshelfUrl = '{{ route('addBookshelf') }}';
            /* token = '{{ csrf_token() }}'; */
    </script>
@endsection
