@extends('template')

@section('title')
    Yönetici Paneli | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="/css/admin/main.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <section class="main">

        <div class="container">
            <div class="header mt-4">
                <h1 class="mt-2 mb-2">Yönetici Paneli</h1>
                <hr>
            </div>

            <div class="row mt-5">
                {{-- <div class="col-sm-12 col-md-6">
                    <a href="{{ route('panelUsers') }}">
                        <div class="users-icon">
                            <x-icons.users />
                        </div>
                    </a>
                </div> --}}
                <div class="col-sm-12">
                    <a href="{{ route('panelBooks') }}">
                        <div class="books-icon">
                            <x-icons.books />
                        </div>
                        
                    </a>
                </div>
            </div>

        </div>

    </section>
@endsection
