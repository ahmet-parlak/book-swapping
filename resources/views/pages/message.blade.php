@extends('template')

@section('title')
    Mesaj | {{ $user->first_name }} {{ substr($user->first_name, 0, 1) }}.
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/messages.css') }}">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    <div class="container mt-5">

        <div class="row offset-3 mt-1 align-items-center">
            <div class="col-2">
                <div class="message-user-pp mt-2 mb-3" title="Kullanıcının Profiline Git">
                    <a href="{{ route('user') }}?user={{ $user->user_id }}" class="user-pp"><img
                            src="../../{{ $user->user_photo }}" alt=""></a>
                </div>
            </div>
            <div class="col-4 text-center">
                <div class="header">
                    {{ $user->first_name }} {{ substr($user->first_name, 0, 1) }}.
                </div>
            </div>
            <div class="trade-icon col-2 text-end">
                <a href="{{ route('messages') }}">
                    <x-icons.messages />
                </a>
            </div>

        </div>
        <div class="row">
            <div class="col-8 offset-2 border-top border-3 mt-2">

            </div>
        </div>

        {{-- Chat --}}
        <div class="space my-2">
            <br>
        </div>
        <section class="message mb-5 ps-3">
            <div id="messages-box">
                @foreach ($messages as $message)
                    <div class="message mt-2 px-3">
                        @php
                            $date = explode(' ', $message->updated_at);
                            $clock = explode(':', $date[1]);
                            $date = explode('-', $date[0]);
                        @endphp

                        <div class="row pe-2">
                            @if ($message->sender != Auth::user()->user_id)
                                <div class="col-5 text-box text-start p-2">
                                @else
                                    <div class="col-7"></div>
                                    <div class="col-5 text-box p-2">
                            @endif

                            <div class="message fw-bold pt-1 ps-2">
                                {{ $message->message }}

                                <div class="date text-end fw-normal">
                                    {{ $date[2] }}/{{ $date[1] }}/{{ $date[0] }}
                                    <span class="ms-2">{{ $clock[0] }}.{{ $clock[1] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            @endforeach
    </div>


    <div class="send text-center mt-5 pt-2 border-top">
        @csrf
        <input id="userInput" name="user" type="text" hidden value="{{ $user->user_id }}">
        <textarea name="message" id="message-box" cols="40" rows="1" placeholder="Mesajınız..." maxlength="200"></textarea>
        <input id="send-button" type="submit" class="btn btn-primary send-btn" value="GÖNDER">
    </div>

    </section>

    </div>

    {{-- @if ($messages->count() >= 5)
        <style>
            .footer {
                position: relative;
                margin-top: 100px;
            }
        </style>
    @endif --}}
    @if ($messages->count() < 7)
        <style>
            #messages-box {
                overflow: hidden;
            }
        </style>
    @endif
@endsection

@section('script')
    <script>
        const url = "{{ route('sendMessage') }}";
        const contact_number = "{{ $contactNum }}";
        /* const token = "{{csrf_token()}}"; */
    </script>
    <script src="{{ asset('js/message.js') }}"></script>
    <script src="{{ asset('js/message_listen.js') }}"></script>
@endsection
