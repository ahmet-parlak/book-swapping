@extends('template')

@section('title')
    Takas | Liboo
@endsection

@section('style')
    <link rel="stylesheet" href="../../css/trade.css">
@endsection

@section('navbar')
    @include('navbar/navbar')
@endsection

@section('body')
    @switch(session('state'))
        @case('cancelled')
            <script>
                swal("Takas iptal edildi", "", "success")
            </script>
        @break

        @case('accepted')
            <script>
                swal("Takas Kabul Edildi", "Kitap Teslim Aşamasına Geçildi",
                    "success")
            </script>
        @break

        @case('done')
            <script>
                swal("Takas Tamamlandı", "Kitapları Teslim Aldığınızı Onayladınız",
                    "success")
            </script>
        @break

        @case('error')
            <script>
                swal("Hata", "", "error")
            </script>
        @break

        @case('db error')
            <script>
                swal("Takas iptal edilirken hata meydana geldi", "", "error")
            </script>
        @break

        @case('select book')
            <script>
                swal({
                    title: "",
                    text: "Lütfen Takas Yapılacak Kitapları Seçin",
                    button: "Tamam",
                    icon: "warning",

                });
            </script>
        @break
    @endswitch

    <div class="container mt-4">

        @if (isset($trade))
            @if ($trade->state == 'active')
                <script>
                    const trade = "active";
                </script>
                <form id="tradeOfferForm" action="{{ route('tradeofferupdate') }}" method="POST">
                @else
                    <script>
                        const trade = "passive";
                    </script>
            @endif
        @else
            <form id="tradeOfferForm" action="{{ route('tradeoffer') }}" method="POST">
        @endif

        @csrf
        {{-- Header --}}

        <div class="row text-center align-items-center mt-4">
            <div class="col-2 offset-2 swap-icon">
                <x-icons.swap />
            </div>
            <div class="col-4 ps-5">
                <div class="header">
                    @if (isset($trade))
                        @switch($trade->state)
                            @case('accepted')
                                Kitap Teslim Aşaması
                            @break

                            @case('done')
                                TAKAS TAMAMLANDI
                            @break

                            @default
                                TAKAS İŞLEMİ
                        @endswitch
                    @else
                        TAKAS TEKLİFİ
                    @endif
                    <span title="Takas Numarası">
                        @isset($trade)
                            {{ $trade->trade_number }}
                        @endisset
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="user-pp mt-2" title="Takas Yapılacak Kullanıcı">

                    <div class="row offset-2 align-items-center">
                        <div class="col-8">
                            <a href="{{ route('user') }}?user={{ $user->user_id }}"><img
                                    src="../../{{ $user->user_photo }}" alt=""></a>
                            <a href="{{ route('user') }}?user={{ $user->user_id }}"><span
                                    class="name-span">{{ $user->first_name }}
                                    {{ substr($user->last_name, 0, 1) }}.</span></a>
                        </div>
                        <div class="col-4 text-start mb-3">
                            <span class="envelope-span"> <a href="{{ route('toUser', $user->user_id) }}"
                                    title="Mesaj Gönder">
                                    <x-icons.envelope />
                                </a></span>
                        </div>
                    </div>


                </div>
            </div>

        </div>

        {{-- Trade --}}
        <div class="row text-center p-2 mt-3">
            {{-- Given --}}
            <div class="col-6 border-end p-2">
                <div class="swap-given pt-2">
                    <h4 class="ps-2" title="Takasta Vereceğiniz Kitaplar">VERECEKLERİM</h4>
                    <table class="table table-borderless align-middle book-table my-4">
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($books as $book)
                                @if (!isset($book->bookshelf_state) || $book->book_id == $book->trades_book_id || $book->bookshelf_state == 'active')
                                    <tr>
                                        <td class="img">
                                            <div class="book-img"><img src="../../{{ $book->image }}" alt=""></div>
                                        </td>
                                        <td class="name"><a
                                                href="{{ route('book') }}?book={{ $book->book_id }}">{{ $book->book_name }}</a>
                                        </td>

                                        <td>
                                            @if (isset($trade) && $trade->state == 'active')
                                                @if (isset($book->trades_book_id) && $book->book_id == $book->trades_book_id)
                                                    <input class="form-check-input give" type="checkbox"
                                                        id="{{ $book->book_id }}-give" name="give[{{ $count++ }}]"
                                                        value="{{ $book->book_id }}" checked>
                                                @else
                                                    <input class="form-check-input give" type="checkbox"
                                                        id="{{ $book->book_id }}-give" name="give[{{ $count++ }}]"
                                                        value="{{ $book->book_id }}" hidden>
                                                @endif
                                            @else
                                                <input class="form-check-input give" type="checkbox"
                                                    id="{{ $book->book_id }}-give" name="give[{{ $count++ }}]"
                                                    value="{{ $book->book_id }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Taken --}}
            <div class="col-6 p-2">
                <div class="swap-taken pt-2">
                    <h4 title="Takasta Alacağınız Kitaplar">ALACAKLARIM</h4>
                    <table class="table table-borderless book-table align-middle my-4">
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($userBooks as $book)
                                @if (!isset($book->bookshelf_state) || $book->book_id == $book->trades_book_id || $book->bookshelf_state == 'active')
                                    <tr>
                                        <td class="img">
                                            <div class="book-img"><img src="../../{{ $book->image }}" alt=""></div>
                                        </td>
                                        <td class="name"><a
                                                href="{{ route('book') }}?book={{ $book->book_id }}">{{ $book->book_name }}</a>
                                        </td>

                                        <td>
                                            @if (isset($trade) && $trade->state == 'active')
                                                @if (isset($book->trades_book_id) && $book->book_id == $book->trades_book_id)
                                                    <input class="form-check-input take" type="checkbox"
                                                        id="{{ $book->book_id }}-take" name="take[{{ $count++ }}]"
                                                        value="{{ $book->book_id }}" checked>
                                                @else
                                                    <input class="form-check-input take" type="checkbox"
                                                        id="{{ $book->book_id }}-take" name="take[{{ $count++ }}]"
                                                        value="{{ $book->book_id }}" hidden>
                                                @endif
                                            @else
                                                <input class="form-check-input take" type="checkbox"
                                                    id="{{ $book->book_id }}-take" name="take[{{ $count++ }}]"
                                                    value="{{ $book->book_id }}">
                                            @endif
                                        </td>

                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        @if (!isset($trade))
            <div class="trade-buttons text-center my-4">
                <input type="text" hidden name="user" id="" value="{{ $user->user_id }}">
                <button type="submit" id="make-offer" class="btn btn-primary order-button">
                    Takas Teklif Et
                </button>
            </div>
        @else
            @if ($trade->state == 'active')
                <div class="trade-buttons text-center my-4 update-offer-submit" hidden>
                    <input type="text" hidden name="user" value="{{ $user->user_id }}">
                    <input type="text" hidden name="trade_number" value="{{ $trade->trade_number }}">
                    <button type="submit" class="btn btn-primary order-button" id="updateOfferBtn">

                        @if ($authTradeState->state == 'accepted')
                            Takas Teklifini Güncelle
                        @else
                            Karşı Teklifte Bulun
                        @endif
                    </button>
                </div>
            @endif
        @endif
        </form>


        @if (isset($trade))
            @switch($trade->state)
                @case('active')
                    <div class="row trade-buttons text-center my-4">
                        @switch($authTradeState->state)
                            @case('standby')
                                <div class="alert alert-info col-8 offset-2 mt-2 mb-4"><span
                                        class="user-name">{{ $user->first_name }}
                                        {{ substr($user->last_name, 0, 1) }}.</span> Adlı Kullanıcıdan Takas Teklifi Aldınız
                                    <span class="update-offer">Karşı Teklifte Bulunmak İçin Tıklayın</span>
                                </div>
                                <div class="col-6 text-end">
                                    <form id="tradeofferaccept" action="{{ route('tradeofferaccept') }}" method="POST">
                                        @csrf
                                        <input id="acceptNumberInput" type="text" hidden name="trade_number"
                                            value="{{ $trade->trade_number }}">
                                        <div type="submit" id="acceptBtn" class="btn btn-primary confirm-button">
                                            Takası Kabul Et
                                        </div>
                                    </form>
                                </div>
                            @break

                            @case('accepted')
                                <div class="alert alert-info col-8 offset-2 mt-2 mb-4">Takas Teklifiniz <span
                                        class="user-name">{{ $user->first_name }}
                                        {{ substr($user->last_name, 0, 1) }}.</span> Adlı Kullanıcıya İletildi

                                    <span class="update-offer">Teklifi Güncellemek İçin Tıklayın</span>
                                </div>
                            @break
                        @endswitch
                        @if ($authTradeState->state == 'accepted')
                            <script>
                                const refuseBtnTxt = "cancel";
                            </script>
                            <div class="col-12 text-center">
                            @else
                                <script>
                                    const refuseBtnTxt = "refuse";
                                </script>
                                <div class="col-6 text-start">
                        @endif

                        <form id="tradeofferrefuse" action="{{ route('tradeofferrefuse') }}" method="POST">
                            @csrf
                            <input id="refuseNumberInput" type="text" hidden name="trade_number"
                                value="{{ $trade->trade_number }}">
                            <div type="submit" id="refuseBtn" class="btn btn-primary cancel-button">
                                @if ($authTradeState->state == 'standby')
                                    Takası Reddet
                                @elseif($authTradeState->state == 'accepted')
                                    Takası İptal Et
                                @endif
                            </div>
                        </form>
                    </div>
            </div>
        @break

        @case('done')
            <div class="trade-buttons text-center my-4">
                <div class="btn btn-primary done-button confirmed">
                    Takas Tamamlandı
                </div>
            </div>
        @break

        @case('accepted')
            <div class="trade-buttons text-center my-4 offset-2">
                <div class="alert alert-success w-75 fw-bold">
                    Takas Onaylandı
                </div>
            </div>
            @if ($authTradeState->state == 'done')
                <div class="col-6 offset-3 alert alert-secondary  text-center mt-4" role="alert">
                    <p class="text-dark mt-2" style="font-size: 1.1rem">Kitapları Aldığınızı Onayladınız
                        <br><br>
                        <strong>{{ $user->first_name }}
                            {{ substr($user->last_name, 0, 1) }}. </strong> kitapları teslim aldığını onaylayınca takas
                        tamamlanacak.
                    </p>
                </div>
            @else
                @if ($userTradeState->state == 'done')
                    <div class="col-6 offset-3 alert alert-secondary  text-center mt-4" role="alert">
                        <p class="text-dark mt-2" style="font-size: 1.1rem"><strong>{{ $user->first_name }}
                                {{ substr($user->last_name, 0, 1) }}. </strong> Kitapları Teslim Aldığını Onayladı
                            <br><br>
                            Siz de kitapları teslim aldığınızı onaylayınca takas tamamlanacak.
                        </p>
                    </div>
                @else
                    <div class="col-6 offset-3 alert alert-secondary  text-center mt-4 fw-bold" role="alert">
                        <p class="text-dark mt-2" style="font-size: 1.1rem">Kitapların teslimatı için kullanıcıyla iletişime geçin.
                            <br><br>
                            En sağlıklı ve güvenilir yöntemin elden takas yapmak olduğunu unutmayın!<br><br>Şayet takas yöntemi
                            konusunda
                            anlaşamazsanız takastan vazgeçebilirsiniz. <br><br>Kitaplar elinize ulaştığında takası tamamlayın.
                        </p>
                        <span class="envelope-span"> <a href="{{ route('toUser', $user->user_id) }}" title="Kullanıcıyla İletişime Geç">
                                <x-icons.envelope />
                            </a></span>
                    </div>
                @endif
                <div class="row my-5">
                    <div class="col-6 text-end">
                        <form id="tradeofferdone" action="{{ route('tradeofferdone') }}" method="POST">
                            @csrf
                            <input id="doneNumberInput" type="text" hidden name="trade_number"
                                value="{{ $trade->trade_number }}">
                            <div type="submit" id="doneBtn" class="btn btn-primary done-button">
                                Takası Tamamla
                            </div>
                        </form>
                    </div>
                    <div class="col-6">
                        <form id="tradeoffergiveup" action="{{ route('tradeoffergiveup') }}" method="POST">
                            @csrf
                            <input id="giveupNumberInput" type="text" hidden name="trade_number"
                                value="{{ $trade->trade_number }}">
                            <div type="submit" id="giveupBtn" class="btn btn-primary cancel-button">
                                Takastan Vazgeç
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @break

        @case('cancelled')
            <div class="trade-buttons text-center my-4">
                <div class="btn btn-primary cancel-button cancelled">
                    Takas İptal Edildi
                </div>
            </div>
        @break

    @endswitch
    @endif

    </div>




@endsection


@section('script')
    <script src="../../../js/trade.js"></script>
@endsection
