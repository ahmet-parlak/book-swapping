@extends('template')

@section('title')
    Favoriler | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/bookshelf.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <div class="container mt-5">

        <div class="row mt-1 align-items-center">
            <div class="col-1 offset-3 text-end">
                <x-icons.favorite-book />
            </div>
            <div class="col-2 text-center offset-1">
                <div class="header">
                    FAVORİLER
                </div>
            </div>
        </div>

        {{-- Books --}}

        @if (!$books)
            {{-- Favorites is Null --}}
            <div class="col-8 offset-2">
                <hr>
            </div>
            <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert" style="color:#cc0621 ">
                Favorilere kitap eklemediniz.
            </div>
        @else
            @foreach ($books as $book)
                <div class="col-md-7 my-2 offset-3  border-top border-2 pt-4">
                    <div class="book" id="book-{{ $book->book_id }}">
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
                                <div class="author">Yazar: <span><a href="">{{ $book->author }}</a></span></div>
                                <div class="publisher">Yayınevi: <span><a href="">{{ $book->publisher }}</a></span>
                                </div>
                                <div class="isbn">ISBN: <span>{{ $book->isbn }}</span></div>
                            </div>
                            <div class="col-md-2 align-self-center pb-4">
                                <div class="remove-book mt-3 ">
                                    <div class="remove-book-btn pb-1" book="{{ $book->book_id }}"
                                        data-bs-toggle="tooltip" data-bs-placement="left" title="Favorilerden Kaldır">
                                        <x-icons.remove-heart-icon />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if (count($books)>=1)
        <div class="text-center mt-5 pt-2">
            {{ $books->links() }}
        </div>
        @endif

        <script>
            let removeFavoritesUrl = '{{ route('removeFavorites') }}';
                /* token = '{{ csrf_token() }}'; */
        </script>

    </div>

    {{-- @if ($books->count() > 3)
        <style>
            .footer {
                margin-top: 100px;
                position: static;
            }

        </style>
    @endif --}}
@endsection

@section('script')
    <script src="../js/favorites.js"></script>
@endsection
