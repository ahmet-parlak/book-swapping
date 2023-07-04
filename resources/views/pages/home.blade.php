@extends('template')

@section('title')
    Anasayfa | Liboo
@endsection

@section('navbar')
    @include('navbar/custom')
@endsection

@section('body')
    <section class="main">
        <img id="banner" src="brand/name.png" alt="banner" style="width:8%">
        <div class="header mt-2 border border-2 border-dark rounded-pill">
            <h1 class="mt-2 mb-2 pb-2 border-bottom border-2 border-danger">KİTAP TAKAS PLATFORMUNA HOŞGELDİNİZ</h2>
            <p>Takasa başlamak için <a href="{{ route('login') }}">giriş yapın</a>, henüz bir hesabınız yoksa hemen <a
                    href="{{ route('signin') }}">kayıt olun</a>!</p>
        </div>
    </section>
    <div class="photo text-center mt-4">
        <img id="bookswap" src="/bookswap.png" alt="logo">
    </div>
@endsection
