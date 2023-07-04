@extends('template')

@section('title')
    Takas Geçmişi | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="css/trades.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    @switch(Request::get('state'))
        @case('active trade exist')
            <script>
                swal("Aktif Takas Mevcut", "Bu kullanıcı ile aktif bir takas işlemi mevcut.", "warning");
            </script>
        @break

        @case('trade offer successful')
            <script>
                swal("Takas Teklifi Oluşturuldu ", "", "success");
            </script>
        @break

        @case('trade offer updated')
            <script>
                swal("Takas Teklifi Güncellendi ", "", "success");
            </script>
        @break
    @endswitch


    <div class="container mt-5">

        <div class="row offset-2 mt-1 align-items-center">
            <div class="trade-icon col-2 text-end">
                <x-icons.swap />
            </div>
            <div class="col-4 text-center offset-1">
                <div class="header">
                    TAKASLAR
                </div>
            </div>
            <div class="col-2 pb-4">
                <div class="trade-count mt-4 text-center">
                    <span>{{ $trades->total() }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-8 offset-2 border-top border-3 mt-2">

            </div>
        </div>
        {{-- Trades --}}
        @php
            $users = json_decode($users);
            $i = 0;
        @endphp
        @foreach ($trades as $trade)
            <div class="col-md-8 offset-2 mt-1 mb-2 pt-4">
                <div class="trade">
                    <div class="row text-center">
                        <div class="col-md-2 offset-1">
                            <div class="user-pp pe-4">
                                <a href="{{ route('user') }}?user={{ $users[$i]->user_id }}" target="_blank"><img
                                        class="loading" src="{{ $users[$i]->user_photo }}" alt=""></a>
                                <a href="{{ route('user') }}?user={{ $users[$i]->user_id }}"
                                    target="_blank"><span>{{ $users[$i]->first_name }}
                                        {{ substr($users[$i]->last_name, 0, 1) }}.</span></a>
                            </div>
                        </div>
                        <div class="col-1">
                            @if ($trade->trade_state == 'active')
                                @if ($trade->state == 'standby')
                                    <div class="pt-3" title="Gelen Takas Teklifi">
                                        <x-icons.go />
                                    </div>
                                @else
                                    <div class="pt-3" title="Yapılan Takas Teklifi">
                                        <x-icons.back />
                                    </div>
                                @endif
                            @endif
                        </div>

                        @php
                            $date = explode('-', explode(' ', $trade->created_at)[0]);
                        @endphp
                        <div class="col-md-4 align-middle">
                            <div class="trade-number" title="Takas Numarası"><a
                                    href="{{ route('openTrade', $trade->trade_number) }}">{{ $trade->trade_number }}</a>
                            </div>
                            <div class="trade-date mt-2">
                                <span>{{ $date[2] }}.{{ $date[1] }}.{{ $date[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 align-self-center">
                            <div class="trade-state disabled">
                                @switch($trade->trade_state)
                                    @case('active')
                                        <a href="{{ route('openTrade', $trade->trade_number) }}" title="Git">
                                            <div class="btn btn-warning trade-info">Aktif</div>
                                        </a>
                                    @break

                                    @case('accepted')
                                        <a href="{{ route('openTrade', $trade->trade_number) }}" title="Git">
                                            <div class="btn btn-success trade-info">Onaylandı</div>
                                        </a>
                                    @break

                                    @case('done')
                                        <a href="{{ route('openTrade', $trade->trade_number) }}" title="Git">
                                            <div class="btn btn-primary trade-info rounded p-2">Tamamlandı</div>
                                        </a>
                                    @break

                                    @case('cancelled')
                                        <a href="{{ route('openTrade', $trade->trade_number) }}" title="Git">
                                            <div class="btn btn-danger trade-info">İptal Edildi</div>
                                        </a>
                                    @break
                                @endswitch
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @php
                $i++;
            @endphp
        @endforeach

        @if ($trades->total() == 0)
            <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert" style="color:#cc0621 ">
                <p class="text-dark py-2 border-bottom border-secondary fs-5">Aktif ve Geçmiş Takas İşlemleriniz Burada
                    Listelenir</p>
                <p>Henüz bir takas işlemi yapmadınız.</p>
            </div>
        @endif

        <div class="text-center mt-5 pt-2">
            {{ $trades->links() }}
        </div>
    </div>

   {{--  @if ($trades->count() >= 5)
        <style>
            .footer {
                position: relative;
                margin-top: 200px;
            }

        </style>
    @endif --}}
@endsection
