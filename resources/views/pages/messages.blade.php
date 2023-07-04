@extends('template')

@section('title')
    Mesajlar | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/messages.css') }}">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <div class="container mt-5">

        <div class="row offset-2 mt-1 align-items-center">
            <div class="trade-icon col-2 text-end">
                <x-icons.messages />
            </div>
            <div class="col-4 text-center offset-1">
                <div class="header">
                    Mesaj Kutusu
                </div>
            </div>
            <div class="col-2 pb-4">
                <div class="trade-count mt-4 text-center">
                    <span>{{ $contactCount }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-8 offset-2 border-top border-3 mt-2">

            </div>
        </div>
        {{-- Messages --}}
        @php
            $i = 0;
        @endphp
        @foreach ($contacts as $contact)
            <div class="col-md-8 offset-2 mt-1 mb-2 pt-4">
                <div class="trade">
                    <div class="row text-center align-items-center">
                        <div class="col-md-2 offset-1">
                            <div class="user-pp pe-4">
                                <a href="{{ route('user') }}?user={{ $contact->user }}"><img
                                        class="loading" src="{{ $contact->user_photo }}" alt=""></a>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('toUser', $contact->user) }}"
                                class="fs-5 fw-bold">{{ $contact->first_name }}
                                {{ substr($contact->last_name, 0, 1) }}.</a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('toUser', $contact->user) }}"
                                class="btn-primary p-2">Mesajlar</a>
                        </div>

                    </div>
                </div>
            </div>
            @php
                $i++;
            @endphp
        @endforeach



        @if ($contactCount == 0)
            <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert" style="color:#cc0621 ">
                <p style="margin-bottom:0">Mesaj Yok</p>
            </div>
        @endif

        <div class="text-center mt-5 pt-2">
            {{-- {{ $contacts->links() }} --}}
        </div>
    </div>

    {{-- @if ($contactCount >= 5)
        <style>
            .footer {
                position: relative;
                margin-top: 200px;
            }
        </style>
    @endif --}}
@endsection
