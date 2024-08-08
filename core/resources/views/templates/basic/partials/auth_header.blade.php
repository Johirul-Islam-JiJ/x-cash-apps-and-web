@php $user = auth()->user(); @endphp
<header class="header">
    <div class="header__top">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-3 col-sm-4 text-sm-start text-center d-sm-block d-none">
                    <a class="site-logo site-title" href="{{ route('home') }}">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/dark_logo.png') }}" alt="logo">
                    </a>
                    <a href="javascript:void(0)" class="header-username">{{ $user->fullname }}</a>
                </div>
                <div class="col-lg-9 col-sm-8">
                    <div class="d-flex flex-wrap justify-content-sm-end justify-content-center align-items-center">
                        <ul class="header-top-menu">
                            <li><a href="{{ route('ticket') }}">@lang('Support Ticket')</a></li>
                        </ul>
                        <div class="header-user">
                            <span class="thumb">
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . @$user->image, getFileSize('userProfile')) }}"
                                    alt="@lang('Profile')">
                            </span>
                            <span class="name">{{ $user->username }}</span>
                            <ul class="header-user-menu">
                                <li>
                                    <a href="{{ route('user.profile.setting') }}">
                                        <i class="las la-user-circle"></i>@lang('Profile')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.change.password') }}">
                                        <i class="las la-cogs"></i>@lang('Change Password')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.twofactor') }}">
                                        <i class="las la-bell"></i>@lang('2FA Security')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.qr.code') }}">
                                        <i class="las la-qrcode"></i>@lang('My QRcode')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.logout.other.devices.form') }}">
                                        <i class="las la-laptop"></i>@lang('Logout From Others')
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.logout') }}">
                                        <i class="las la-sign-out-alt"></i>@lang('Logout')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
