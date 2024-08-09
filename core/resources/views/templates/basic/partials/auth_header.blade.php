@php $user = auth()->user(); @endphp

<header class="header__top">
    <div class="container-fluid">
        <div class="header-top-wrapper">
            <div class="left-header">
                <a class="site-logo site-title d-none d-sm-block" href="{{ route('home') }}">
                    <img src="{{ asset('assets/global/images/logo.png') }}" alt="logo">
                </a>
                <span class="body__bar-icon d-xl-none">
                    <i class="fas fa-bars"></i>
                </span>
            </div>

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
</header>


@push('style')
    <style>
        .body__bar-icon {
            display: flex;
            font-size: 18px;
        }

        .left-header {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-top-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
@endpush
