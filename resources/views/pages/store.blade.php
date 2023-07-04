@extends('template')
@section('title')
    Store | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/store.css">
@endsection


@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')

    @switch(request()->get('state'))
        @case('bookshelf empty')
            <script>
                swal({
                    title: "Dikkat",
                    text: "Kitaplığınızda takas yapabileceğiniz aktif kitap yok.",
                    button: "Tamam",
                    icon: "warning",

                });
            </script>
        @break

        @case('userBookNotFound')
            <script>
                swal({
                    title: "Dikkat",
                    text: "Kullanıcının takas yapabileceğiniz bir kitabı yok",
                    button: "Tamam",
                    icon: "warning",

                });
            </script>
        @break
    @endswitch


    <div class="container mt-4">
        <div class="header text-center border-bottom">
            <h1>STORE</h1>
        </div>
    </div>
    <div class="my-3">
        <div class="row me-0">
            <div class="col-md-8 offset-2 mt-2">
                @if ($books)
                    {{-- Search --}}
                    <div class="row offset-2">
                        <div class="col-10">
                            <div class="search">
                                <form id="search-form" class="d-flex search-form">
                                    <input id="searchInput" min="3" autocomplete="off"
                                        class="rounded-0 search-input form-control me-0 fs-5" type="search"
                                        placeholder="Kitap, Yazar, Yayınevi... " aria-label="Search">
                                    <div id="searchButton" class="search-btn" type="submit">
                                        <div class="serach-icon">
                                            <x-icons.search-book />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- Search --}}
                @endif

                @if (!method_exists($books, 'links'))
                    <div class="container mt-5 mb-4">
                        <div class="header text-center border-bottom">
                            <h2>TAKASTAKİLER</h2>
                        </div>
                    </div>
                @endif

                <div class="books mt-4">
                    <div class="row">
                        @if (!$books)
                            <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert"
                                style="color:#cc0621 ">
                                <h3 class="py-2 border-bottom border-secondary fw-bold">Store Bakım Aşamasında</h2>
                                    <p class="text-dark">Tüm kitaplar erişime kapalı.</p>
                            </div>
                        @else
                            @foreach ($books as $book)
                                <div class="col-sm-12 col-xxl-6 mb-2">
                                    <div class="book border border-2 rounded-end">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="book-img">
                                                    <a href="{{ route('book') }}?book={{ $book->book_id }}"><img
                                                            class="book-img" src="{{ $book->image }}"
                                                            alt="book"></a>
                                                </div>
                                            </div>
                                            <div class="col-md-8 align-middle">
                                                <div class="book-name my-1" title="{{ $book->book_name }}"><a
                                                        href="{{ route('book') }}?book={{ $book->book_id }}">
                                                        @if (strlen($book->book_name) >= 30)
                                                            {{ substr($book->book_name, 0, 30) . '...' }}
                                                        @else
                                                            {{ $book->book_name }}
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="author">Yazar: <span><a
                                                            href="{{ route('store') }}?search={{ $book->author }}">{{ $book->author }}</a></span>
                                                </div>
                                                <div class="publisher">Yayınevi: <span><a
                                                            href="{{ route('store') }}?search={{ $book->publisher }}">{{ $book->publisher }}</a></span>
                                                </div>
                                                <div class="isbn">ISBN: <span
                                                        style="user-select:text">{{ $book->isbn }}</span></div>
                                            </div>
                                            {{-- Icons --}}
                                            <div class="col-md-2 mt-4">
                                                <div class="in-swap position-relative" class="book-box-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="left"
                                                    title="Takas Edilebilir: {{ $book->intrade }}">
                                                    <x-icons.bookbox /> <span
                                                        class="ms-2 position-absolute top-0 start-1 translate-middle">{{ $book->intrade }}</span>
                                                </div>
                                                @if (in_array($book->book_id, $bookshelf))
                                                    <div class="add-to-bookshelf mt-2">
                                                        <div class="in-bookshelf-icon" data-bs-toggle="tooltip"
                                                            data-bs-placement="left" title="Kitaplıkta">
                                                            <x-icons.bookshelf />
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="add-to-bookshelf mt-1">
                                                        <div class="add-bookshelf-icon" book="{{ $book->book_id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                            title="Kitaplığa Ekle">
                                                            <x-icons.add-to-bookshelf />
                                                        </div>
                                                    </div>
                                                    <div class="add-to-bookshelf mt-2">
                                                        <div id="{{ $book->book_id }}-inshelf"
                                                            class="in-bookshelf-icon d-none" data-bs-toggle="tooltip"
                                                            data-bs-placement="left" title="Kitaplıkta">
                                                            <x-icons.bookshelf />
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (!in_array($book->book_id, $favorites))
                                                    <div class="add-list mt-3">
                                                        <div book="{{ $book->book_id }}" class="add-favorites-icon"
                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                            title="Favorilere Ekle">
                                                            </a>
                                                            <x-icons.bookmark-heart />
                                                        </div>
                                                        <div id="{{ $book->book_id }}-star"
                                                            class="in-favorites d-none  pb-1 ps-1" data-bs-toggle="tooltip"
                                                            data-bs-placement="left" title="Favorilerde">
                                                            <x-icons.heart-icon />
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="in-favorites  mt-3 ps-1" data-bs-toggle="tooltip"
                                                        data-bs-placement="left" title="Favorilerde">
                                                        <x-icons.heart-icon />
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    @if ($books->count() == 0)
                        <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert"
                            style="color:#cc0621 ">
                            <h4 class="py-2 mb-3 border-bottom border-secondary fw-bold">Maalesef Aradığınız Kitabı
                                Bulamadık
                            </h4>
                            <p class="text-dark" style="font-size: 1.2rem">Aradığınız kitabı en kısa sürede sisteme
                                eklememiz için bize yardımcı olabilirsiniz.
                            </p>
                            <a href="{{ route('addbook') }}" class="btn btn-primary">Kitap Ekle</a>
                        </div>
                    @endif
                    @if (method_exists($books, 'links'))
                        <div class="mt-5 text-center">
                            {{ $books->links() }}
                        </div>
                    @endif

                </div>

            </div>

        </div>
        <br><br>
        {{-- TOP FAVORITES --}}
        @if (count($topFavorites) > 0)
            <div class="container my-5">
                <div class="header text-center border-bottom">
                    <h2>FAVORİLER</h2>
                </div>

                <div class="books mt-4 text-center">
                    <div class="row">
                        @foreach ($topFavorites as $book)
                            <div class="col-2">
                                <div class="book-img">
                                    <a href="{{ route('book') }}?book={{ $book->book_id }}"><img
                                            class="book-img" src="{{ $book->image }}" alt="book"></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
    <div style="height: 60px"></div>
    <script>
        let addFavoritesUrl = '{{ route('addFavorites') }}',
            addBookshelfUrl = '{{ route('addBookshelf') }}',
            storeUrl = '{{ route('store') }}';
        /* token = '{{ csrf_token() }}'; */
    </script>

    {{-- @if ($books->count() < 4)
        <style>
            .footer {
                position: absolute;
                bottom: 0;
                left: 0;
            }
        </style>
    @endif --}}

@endsection

@section('script')
    <script src="../js/store.js"></script>
@endsection
