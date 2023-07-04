<nav class="navbar navbar-expand-lg navbar-light bg-light py-0">
    <div class="container-fluid">
        <a class="navbar-brand ms-1 py-0" href="{{ route('home') }}"><img src="{{ url('brand/logo.png') }}"
                alt=""></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ms-4" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item ms-4 me-5 text-center" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Store">
                    <a href="{{ route('store') }}">
                        <x-icons.bookstore />
                        {{-- <span class="d-block">STORE</span> --}}
                    </a>
                </li>
                <li class="nav-item me-5 text-center" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Takaslar">
                    <a class="mytrades" href="{{ route('mytrades') }}">
                        <x-icons.swap />
                        {{-- <span class="d-block ">TAKAS</span> --}}
                    </a>
                </li>
                <li class="nav-item text-center me-5" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Kitaplık">
                    <a class="bookshelf " href="{{ route('bookshelf') }}">
                        <x-icons.bookshelf />
                        {{-- <span class="d-block ">KİTAPLAR</span> --}}
                    </a>
                </li>
                @if (Auth::user()->type == 'admin')
                    <li class="nav-item text-center" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Yönetici Paneli">
                        <a class="panel " href="{{ route('panel') }}">
                            <x-icons.admin />
                            {{-- <span class="d-block ">KİTAPLAR</span> --}}
                        </a>
                    </li>
                @endif


            </ul>

        </div>
        <div class="d-flex align-items-center">
            {{-- Notifications --}}
            <div class="collapse navbar-collapse me-2">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link " role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-notifications" data-bs-toggle="tooltip" data-bs-placement="left"
                                title="Bildirimler">
                                <x-icons.bell-fill />
                                <span
                                    class="bell-badge position-absolute translate-middle p-2 bg-danger border border-light rounded-circle"
                                    @if ($notifications->count() == 0) style="display:none;" @endif></span>

                            </div>
                        </a>
                        <ul class="dropdown-menu notifications-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                            @foreach ($notifications as $notification)
                                <li
                                    class="row not-dropdown-item align-middle align-items-center notification-item mb-2">
                                    <div class="col-2 not-user-pp text-end">
                                        <img src="{{ asset($notification->user_photo) }}" alt="">
                                    </div>
                                    <div class="col-10 ms-0 ps-0"><a href="{{ $notification->link }}"
                                            class="notification-link"
                                            notification="{{ $notification->id }}"><strong>{{ $notification->first_name }}
                                                {{ substr($notification->first_name, 0, 1) }}.</strong>
                                            {{ $notification->message }} </a>
                                    </div>
                                </li>
                            @endforeach
                            <li>
                                @if ($notifications->count() == 0)
                                    <div class="px-5 notifications-empty">
                                        <div class="alert alert-secondary p-1 mb-0 text-center">
                                            <strong><small>Bildirim Yok</small></strong>
                                        </div>
                                    </div>
                                    <div class="div text-center clear-notifications" style="display: none">
                                        <strong>Bildirimleri Temizle</strong>
                                    </div>
                                @else
                                    <div class="div text-center clear-notifications">
                                        <strong>Bildirimleri Temizle</strong>
                                    </div>
                                    <div class="px-5 notifications-empty" style="display: none">
                                        <div class="alert alert-secondary p-1 mb-0 text-center">
                                            <strong><small>Bildirim Yok</small></strong>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        </ul>

                    </li>

                </ul>
            </div>

            {{-- Messages --}}
            <a class="nav-messages mx-3" href="{{ route('messages') }}" data-bs-toggle="tooltip"
                data-bs-placement="bottom" title="Mesajlar">
                <x-icons.messages />
            </a>

            {{-- Profile --}}
            <div class="collapse navbar-collapse me-4" id="navbarNavDarkDropdown">
                <ul class="navbar-nav align-items-center">

                    <li class="nav-item dropdown">

                        <a class="nav-link " href="#" id="navbarDarkDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-user-pp text-center">
                                <img src="../../{{ Auth::user()->user_photo }}" alt="">
                                <span>{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}.
                                    {{ ucfirst(Auth::user()->last_name) }}</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item align-middle" href="{{ route('bookshelf') }}">
                                    <div class="row">
                                        <div class="col-2">
                                            <x-icons.bookshelf />
                                        </div>
                                        <div class="col-6">Kitaplık</div>
                                    </div>
                                </a></li>

                            <li><a class="dropdown-item align-middle" href="{{ route('favorites') }}">
                                    <div class="row">
                                        <div class="col-2">
                                            <x-icons.favorite-book />
                                        </div>
                                        <div class="col-6">Favoriler</div>
                                    </div>
                                </a></li>
                            <li><a class="dropdown-item align-middle" href="{{ route('myprofile') }}">
                                    <div class="row">
                                        <div class="col-2">
                                            <x-icons.profile />
                                        </div>
                                        <div class="col-6">Profil</div>
                                    </div>
                                </a></li>
                            @if (Auth::user()->type == 'admin')
                                <li><a class="dropdown-item align-middle" href="{{ route('panel') }}">
                                        <div class="row">
                                            <div class="col-2">
                                                <x-icons.admin />
                                            </div>
                                            <div class="col-6">Panel</div>
                                        </div>
                                    </a></li>
                            @endif
                        </ul>
                    </li>

                </ul>
            </div>

            <a href="{{ route('logout') }}">
                <div class="nav-btn">ÇIKIŞ</div>
            </a>
        </div>
    </div>
</nav>
