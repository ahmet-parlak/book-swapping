@extends('template')

@section('title')
    Kitaplar | Panel
@endsection

@section('style')
    <link rel="stylesheet" href="/css/admin/main.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    @switch(app('request')->input('pageState'))
        @case('Delete Success')
            <script>
                swal('Kitap Silindi', '', 'success')
            </script>
        @break

        @case('Delete Error')
            @if (app('request')->input('error') == 'book in trade')
                <script>
                    swal('Kitap Silinemedi', 'Bu kitap akitf olarak ya da daha önce bir takasta kullanıldığı için silinemiyor.',
                        'error')
                </script>
            @endif
        @break
    @endswitch


    <section class="main">

        <div class="container">
            <div class="header mt-4">
                <h1 class="mt-2 mb-2">KİTAPLAR</h1>
                <hr>
            </div>
            {{-- Book States --}}
            @php
                $active = 0;
                $passive = 0;
                $waiting = 0;
                foreach ($book_state as $state) {
                    switch ($state->state) {
                        case 'active':
                            $active = $state->total;
                            break;
                        case 'passive':
                            $passive = $state->total;
                            break;
                        case 'waiting':
                            $waiting = $state->total;
                            break;
                    }
                }
                $total = $active + $passive + $waiting;
            @endphp
            <div class="books-info border w-75 mb-5 py-1">
                {{-- Beklemede --}}
                <div class="d-inline me-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Onay Bekliyor">
                    <a href="{{ route('panelBooks') }}?search=waiting">
                        <x-icons.waiting />
                    </a> <span>{{ $waiting }}</span>
                </div>

                {{-- Aktif --}}
                <div class="d-inline me-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Aktif">
                    <a href="{{ route('panelBooks') }}?search=active">
                        <x-icons.verified />
                    </a> <span>{{ $active }}</span>
                </div>

                {{-- Pasif --}}
                <div id="passive-icon" class="d-inline me-5 " data-bs-toggle="tooltip" data-bs-placement="top" title="Pasif">
                    <a href="{{ route('panelBooks') }}?search=passive">
                        <x-icons.verified-passive />
                    </a> <span class="ms-1">{{ $passive }}</span>
                </div>

                {{-- Toplam --}}
                <div class="d-inline me-5" data-bs-toggle="tooltip" data-bs-placement="top" title="Tüm Kitaplar">
                    <a href="{{ route('panelBooks') }}">
                        <x-icons.books />
                    </a> <span class="ms-1">{{ $total }}</span>

                </div>
                <div id="passive-info" class="pe-5 mt-2 d-none passive-info" style="position:absolute; left:44%;"><small
                        class="p-1 bg-secondary bg-opacity-10 rounded">Kullanıcı erişimine kapatılan
                        kitaplar</small> </div>
            </div>


            {{-- Search --}}
            <div class="search col-8 offset-2 my-4">
                <form id="search-form" class="d-flex search-form">
                    <input id="searchInput" autocomplete="off" class="search-input form-control me-0 fs-5" type="search"
                        placeholder="ISBN, Kitap, Yazar, Yayınevi... " aria-label="Search">
                    <button id="searchButton" type="submit" class="btn btn-secondary">
                        <x-icons.search />
                    </button>
                </form>
            </div>





            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">KİTAP</th>
                        <th scope="col">YAZAR</th>
                        <th scope="col">YAYINEVİ</th>
                        <th scope="col">TALEP</th>
                        <th scope="col">DURUM</th>
                        <th scope="col" class="px-3 py-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Kitap Ekle"><a href="{{ route('panelAddBook') }}">
                                <x-icons.add-book />
                            </a></th>
                    </tr>
                </thead>
                <tbody class="books">
                    @php
                        if (Request::get('page') && Request::get('page') > 1) {
                            $count = (Request::get('page') - 1) * 10 + 1;
                        } else {
                            $count = 1;
                        }
                    @endphp

                    @foreach ($books as $book)
                        <tr>
                            <th scope="row">{{ $count++ }}</th>
                            <td style="user-select: all">{{ $book->isbn }}</td>
                            <td title="{{ $book->book_name }}"><a
                                    href="{{ route('book') }}?book={{ $book->book_id }}"
                                    target="_blank">@php
                                        if (strlen($book->book_name) >= 25) {
                                            echo substr($book->book_name, 0, 25) . '...';
                                        } else {
                                            echo $book->book_name;
                                        }
                                    @endphp</a></td>

                            <td title="{{ $book->author }}"><a
                                    href="{{ route('panelBooks') }}?search={{ $book->author }}">@php
                                        if (strlen($book->author) >= 25) {
                                            echo substr($book->author, 0, 25) . '...';
                                        } else {
                                            echo $book->author;
                                        }
                                    @endphp</a>
                            </td>
                            <td title="{{ $book->publisher }}"><a
                                    href="{{ route('panelBooks') }}?search={{ $book->publisher }}">@php
                                        if (strlen($book->publisher) >= 25) {
                                            echo substr($book->publisher, 0, 25) . '...';
                                        } else {
                                            echo $book->publisher;
                                        }
                                    @endphp</a>
                            </td>
                            <td>{{ $book->demand == 0 ? '-' : $book->demand }}</td>
                            <td>
                                @switch($book->state)
                                    @case('active')
                                        <span class="text-success">Aktif</span>
                                    @break

                                    @case('passive')
                                        <span class="text-danger">Pasif</span>
                                    @break

                                    @case('waiting')
                                        <span class="text-warning">Onay Bekliyor</span>
                                    @break

                                    @default
                                @endswitch
                            </td>
                            <td data-bs-toggle="tooltip" data-bs-placement="right" title="Düzenle"><a class="edit-icon"
                                    href="updatebook?book={{ $book->book_id }}">
                                    <x-icons.edit-box />
                                </a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($books->currentPage() > 1 && $books->count() == 0)
                <script>
                    location.href = "{{ route('panelBooks') }}";
                </script>
            @endif

            @if ($books->currentPage() == 1 && $books->count() == 0)
                <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert">
                    Kitap Bulunamadı
                </div>
            @else
                <div class="mt-4">
                    {{ $books->links() }}
                </div>
            @endif


            <script>
                searchUrl = '{{ route('panelBooks') }}'
            </script>
    </section>

   {{--  @if ($books->count() >= 10)
        <style>
            .footer {
                margin-top: 100px;
                position: static;
            }
        </style>
    @endif --}}
@endsection

@section('script')
    <script src="../js/admin/books.js"></script>
@endsection
