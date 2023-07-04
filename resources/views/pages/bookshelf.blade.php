@extends('template')

@section('title')
    Kitaplık | Liboo
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
            <div class="bookshelf-icon col-1 offset-3 text-end">
                <x-icons.mybooks />
            </div>
            <div class="col-2 text-center offset-1">
                <div class="header">
                    KİTAPLARIM
                </div>
            </div>
            <div class="col-2 text-center">

            </div>

            @if ($books)
                <div class="col-1 pb-4" id="info-click" title="Bilgi" data-bs-toggle="tooltip" data-bs-placement="left">
                    <div class="info mt-4" style="cursor:pointer" data-bs-toggle="popover"
                        data-bs-content="Diğer kullanıcılar <strong>Aktif</strong> kitaplarınıza takas teklifinde bulunabilir."
                        data-bs-html="true">
                        <x-icons.info />
                    </div>
                </div>
            @endif
        </div>

        {{-- Books --}}
        <script>
            let isBooks = "{{$books->total()}}";
        </script>
        @if ($books->total())
            @foreach ($books as $book)
                <div class="col-md-7 my-2 offset-3  border-top border-2 pt-4">
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
                                <div class="author">Yazar: <span><a href="">{{ $book->author }}</a></span></div>
                                <div class="publisher">Yayınevi: <span><a href="">{{ $book->publisher }}</a></span>
                                </div>
                                <div class="isbn">ISBN: <span>{{ $book->isbn }}</span></div>
                            </div>
                            <div class="col-md-2 align-self-center">
                                @if ($book->bookshelf_state == 'active')
                                    <div class="book-state disabled">
                                        <button book="{{ $book->book_id }}" class="btn btn-primary disable"
                                            data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="Pasif Yap">AKTİF</button>
                                    </div>
                                @else
                                    <div class="book-state disabled">
                                        <button book="{{ $book->book_id }}" class="btn btn-primary activate"
                                            data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="Aktif Et">PASİF</button>
                                    </div>
                                @endif

                                <div class="remove-book mt-3">
                                    <div book="{{ $book->book_id }}" class="pb-2 ms-4 remove-book"
                                        data-bs-toggle="tooltip" data-bs-placement="right" title="Kitaplıktan Kaldır">
                                        <x-icons.delete />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-8 offset-2">
                <hr>
            </div>
            <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert" style="color:#cc0621 ">
                <p class="text-dark py-2 border-bottom border-secondary fs-5">Kitaplığınızda takasta kullanacağınız kitaplar
                    bulunur.</p>
                <p> Henüz kitaplığa kitap eklemediniz.</p>
                <p>Kitap eklemek için <a href="{{ route('store') }}">Store</a>'u ziyaret edin.</p>
            </div>
        @endif
    </div>

    <div class="text-center mt-5 pt-2">
        {{$books->links()}}
    </div>
    
    {{-- @if ($books->count() > 3)
        <style>
            .footer {
                margin-top: 100px;
                position: static;
            }

        </style>
    @endif --}}

    <script>
        let activateUrl = '{{ route('activateBookshelf') }}',
            disableUrl = '{{ route('disableBookshelf') }}',
            removeUrl = '{{ route('removeBookshelf') }}';
        /* token = '{{ csrf_token() }}'; */
    </script>
@endsection
@section('script')
    <script src="../js/bookshelf.js"></script>
@endsection
