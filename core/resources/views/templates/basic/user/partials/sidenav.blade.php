@php
    $balanceWallets = App\Models\Wallet::hasCurrency()
        ->where('user_id', auth()->id())
        ->where('user_type', 'USER')
        ->where('balance', '>', 0)
        ->with('currency')
        ->get(['currency_id', 'balance']);
@endphp
<div class="col-lg-3 d-none">
    <div class="custom--card mb-4">
        <div class="card-body">
            <h6 class="mb-4 font-size--16px">{{ __($general->site_name) }} @lang('Balance')</h6>
            <h2 class="fw-normal main-balance main__amount__responsive">
                {{ $general->cur_sym }}{{ showAmount(array_sum(@$totalBalance), $general->currency) }}
                {{ $general->cur_text }}<sup>*</sup></h2>
            <div class="d-flex flex-wrap align-items-center justify-content-between mt-3">
                <p class="text-muted">@lang('Availabe')</p>
                <a href="{{ route('user.wallets') }}" class="font-size--14px text--base d-lg-none">@lang('More Wallets') 
                    <i class="las la-long-arrow-alt-right"></i>
                </a>
            </div>
            <ul class="caption-list-two mt-2">
                @foreach ($balanceWallets as $wallet)
                    <li>
                        <span class="caption">{{ $wallet->currency->currency_code }}</span>
                        <span class="value">{{ $wallet->currency->currency_symbol }}
                            {{ showAmount($wallet->balance, $wallet->currency) }}
                        </span>
                    </li>
                @endforeach
            </ul>
            <p class="font-size--12px mt-2">* @lang('Estimate total balance based on the most recent conversion rate .')</p>
            @if (module('transfer_money', $module)->status)
                <a href="{{ route('user.transfer') }}" class="btn btn--base btn-sm d-block mt-4">@lang('Transfer Money')</a>
            @endif
        </div>
    </div><!-- custom--card end -->
    <div class="custom--card mobile-quick-links mb-5">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-6">
                    <h6>@lang('Quick Links')</h6>
                </div>
            </div>
            <div class="row justify-content-center gy-4">
                @include($activeTemplate . 'user.partials.quick_links')
            </div><!-- row end -->
        </div>
    </div><!-- custom--card end -->
    <div class="row align-items-center mb-3">
        <div class="col-6">
            <h6 class="fw-normal">@lang('Insights')</h6>
        </div>
        <div class="col-6 text-end">
            <div class="dropdown custom--dropdown has--arrow">
                <button class="text-btn dropdown-toggle font-size--14px text--base" type="button"
                    id="latestAcvitiesButton" data-bs-toggle="dropdown" aria-expanded="false">
                    @lang('Select')
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="latestAcvitiesButton">
                    <li>
                        <a class="dropdown-item money" data-day="7" href="javascript:void(0)">@lang('Last 7 days')</a>
                    </li>
                    <li>
                        <a class="dropdown-item money" data-day="15" href="javascript:void(0)">@lang('Last 15 days')</a>
                    </li>
                    <li>
                        <a class="dropdown-item money" data-day="31" href="javascript:void(0)">@lang('Last month')</a>
                    </li>
                    <li>
                        <a class="dropdown-item money" data-day="365" href="javascript:void(0)">@lang('Last year')</a>
                    </li>
                </ul>
            </div>
        </div>
    </div><!-- row end -->
    <div class="custom--card mb-4">
        <div class="card-body">
            <h6 class="mb-4 font-size--16px">@lang('Money in') <small class="text--muted last-time">(
                    @lang('last 7 days') )</small></h6>
            <h3 class="fw-normal money-in">
                {{ $general->cur_sym }}{{ showAmount($totalMoneyInOut['totalMoneyIn'], $general->currency) }}
                {{ $general->cur_text }}<sup>*</sup>
            </h3>
            <a href="{{ route('user.transactions', ['type'=>'plus_trx']) }}"
                class="text--link text-muted font-size--14px">@lang('Total received')
            </a>
            <div class="d-flex flex-wrap align-items-center justify-content-between mt-4">
                @if (module('request_money', $module)->status)
                    <a href="{{ route('user.request.money') }}" class="font-size--14px fw-bold">@lang('Request Money')</a>
                @endif
                <a href="{{ route('user.transactions', ['type'=>'plus_trx']) }}"
                    class="font-size--14px fw-bold">@lang('View Transactions')
                </a>
            </div>
        </div>
    </div><!-- custom--card end -->
    <div class="custom--card">
        <div class="card-body">
            <h6 class="mb-4 font-size--16px">@lang('Money out') <small class="text--muted last-time">(
                    @lang('last 7 days') )</small> </h6>
            <h3 class="fw-normal money-out">
                {{ $general->cur_sym }}{{ showAmount($totalMoneyInOut['totalMoneyOut'], $general->currency) }}
                {{ $general->cur_text }}<sup>*</sup>
            </h3>
            <a href="{{ route('user.transactions', ['type'=>'minus_trx']) }}"
                class="text--link text-muted font-size--14px">@lang('Total spent')
            </a>
            <div class="d-flex flex-wrap align-items-center justify-content-between mt-4">
                @if (module('transfer_money', $module)->status)
                    <a href="{{ route('user.transfer') }}" class="font-size--14px fw-bold">@lang('Send Money')</a>
                @endif
                <a href="{{ route('user.transactions', ['type'=>'minus_trx']) }}"
                    class="font-size--14px fw-bold">@lang('View Transactions')
                </a>
            </div>
        </div>
    </div><!-- custom--card end -->
</div>
<div class="dashboard">
    <div class="sidebar-menu flex-between">
        <div class="sidebar-menu__inner">
            <span class="sidebar-menu__close d-lg-none d-block"><i class="fas fa-times"></i></span>
            <!-- Sidebar Logo Start -->
            <div class="sidebar-logo">
                <a href="index.html" class="sidebar-logo__link"><img src="assets/images/logo/logo.png" alt=""></a>
            </div>
            <!-- Sidebar Logo End -->
        
            <!-- ========= Sidebar Menu Start ================ -->
            <ul class="sidebar-menu-list">
                
                <li class="menu-title pt-0">MENU</li>
                
                <li class="sidebar-menu-list__item active">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li> 
                
                <li class="sidebar-menu-list__item">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-file-invoice"></i></span>
                        <span class="text">About</span>
                    </a>
                </li>
        
                <li class="sidebar-menu-list__item">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                        <span class="text">Resources</span>
                    </a>
                </li>
        
                <li class="sidebar-menu-list__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-globe"></i></span>
                        <span class="text">Language</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <li class="sidebar-submenu-list__item">
                                <a href="#" class="sidebar-submenu-list__link">
                                    <span class="text">Bangla </span>
                                </a>
                            </li>
                            <li class="sidebar-submenu-list__item">
                                <a href="#" class="sidebar-submenu-list__link">
                                    <span class="text">English </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> 
        
                <hr>
                <li class="menu-title pt-0">SUPPORT</li>
                <li class="sidebar-menu-list__item">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-phone"></i></span>
                        <span class="text">Contact</span>
                    </a>
                </li>
                <li class="sidebar-menu-list__item">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="text">Log Out</span>
                    </a>
                </li>
            </ul>
            <!-- ========= Sidebar Menu End ================ -->
        </div>
    </div>
</div>

@push('script')
    <script>
        'use strict';
        (function ($) {
            $('.money').on('click', function () {
                var url = "{{ route('user.check.insight') }}";
                var day = $(this).data('day');
                var text = $(this).text();
                var data = {
                    day:day
                }
                $.get(url,data,function(response) {
                    if(response.error){
                        notify('error',response.error)
                        return false;
                    }
                    var moneyIn = response.totalMoneyIn;
                    var moneyOut = response.totalMoneyOut;
                    var curSym = '{{$general->cur_sym}}';
                    var curTxt = '{{$general->cur_text}}';

                    $('.money-in').text(curSym+moneyIn.toFixed(2)+' '+curTxt);
                    $('.money-out').text(curSym+moneyOut.toFixed(2)+' '+curTxt);
                    $('.last-time').text('( '+text.toLowerCase()+' )');
                    $('#latestAcvitiesButton').text(text);               
                });
            });
        })(jQuery);
    </script>
@endpush


@push('script')
    <script>
        $(".has-dropdown > a").click(function () {
        $(".sidebar-submenu").slideUp(200);
        if ($(this).parent().hasClass("active")) {
            $(".has-dropdown").removeClass("active");
            $(this).parent().removeClass("active");
        } else {
            $(".has-dropdown").removeClass("active");
            $(this).next(".sidebar-submenu").slideDown(200);
            $(this).parent().addClass("active");
        }
        });
        // Sidebar Dropdown Menu End
        // Sidebar Icon & Overlay js
        $(".dashboard-body__bar-icon").on("click", function () {
        $(".sidebar-menu").addClass("show-sidebar");
        $(".sidebar-overlay").addClass("show");
        });
        $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
        $(".sidebar-menu").removeClass("show-sidebar");
        $(".sidebar-overlay").removeClass("show");
        });
    </script>
@endpush

@push("style")
        <style>
            
            .dashboard .sidebar-menu {
                height: 100vh;
                background-color: hsl(var(--white));
                overflow-y: auto;
                z-index: 999;
                transition: 0.2s linear;
                width: 300px;
                border-right: 1px solid hsl(var(--base-two)/0.15);
                border-radius: 0;
                position: fixed;
                left: 0;
                top: 0;
                align-items: start;
                flex-direction: row;
            /* Menu Title Css */
            /* Hr Css */
            }

            .dashboard .sidebar-menu::-webkit-scrollbar {
                width: 3px;
                height: 3px;
            }

            .dashboard .sidebar-menu::-webkit-scrollbar-thumb {
                background-color: hsl(var(--black)/0.15);
            }

            .dashboard .sidebar-menu__inner {
                padding: 0 24px;
                width: 100%;
            }

            .dashboard .sidebar-menu.show-sidebar {
                transform: translateX(0);
            }

            @media screen and (max-width: 991px) {
                .dashboard .sidebar-menu {
                    transform: translateX(-100%);
                    z-index: 9992;
                    border-radius: 0;
                }
            }

            .dashboard .sidebar-menu__close {
                position: absolute;
                top: 8px;
                right: 16px;
                color: hsl(var(--body-color));
                font-size: 1.25rem;
                transition: 0.2s linear;
                cursor: pointer;
                z-index: 9;
            }

            .dashboard .sidebar-menu__close:active {
                top: 14px;
            }

            .dashboard .sidebar-menu__close:hover, .dashboard .sidebar-menu__close:focus {
                background-color: hsl(var(--white));
                border-color: hsl(var(--white));
                color: hsl(var(--base));
            }

            .dashboard .sidebar-menu .menu-title {
                letter-spacing: 0.9px;
                padding: 12px 15px;
                color: #9da9b5;
                font-weight: 600;
            }

            .dashboard .sidebar-menu hr {
                opacity: 0.15;
            }

            .dashboard .sidebar-menu-list {
                margin-top: 40px;
            }

            .dashboard .sidebar-menu-list__item {
                margin-bottom: 6px;
            }

            .dashboard .sidebar-menu-list__item:last-child .sidebar-menu-list__link {
                border-bottom: 0;
            }

            .dashboard .sidebar-menu-list__item.active > a {
                background-color: hsl(var(--base)/0.1);
                color: hsl(var(--base));
            }

            .dashboard .sidebar-menu-list__item.has-dropdown.active > a {
                color: hsl(var(--base));
            }

            .dashboard .sidebar-menu-list__item.has-dropdown.active > a:after {
                transform: rotate(90deg);
                right: 18px;
                color: hsl(var(--base));
            }

            .dashboard .sidebar-menu-list__item.has-dropdown > a:after {
                position: absolute;
                content: "\f105";
                font-family: "Font Awesome 5 Free";
                font-weight: 900;
                font-style: normal;
                display: inline-block;
                font-style: normal;
                font-variant: normal;
                text-rendering: auto;
                text-align: center;
                background: 0 0;
                right: 16px;
                top: 14px;
                transition: 0.1s linear;
                color: hsl(var(--body-color)/0.6);
            }

            .dashboard .sidebar-menu-list__link {
                display: inline-block;
                text-decoration: none;
                position: relative;
                padding: 12px 15px;
                width: 100%;
                color: #6b717e;
                font-weight: 500;
                border-radius: 5px;
            }

            .dashboard .sidebar-menu-list__link:hover {
                background-color: hsl(var(--base)/0.06);
            }

            .dashboard .sidebar-menu-list__link.active {
                color: hsl(var(--base));
            }

            .dashboard .sidebar-menu-list__link .icon {
                margin-right: 8px;
                text-align: center;
                border-radius: 4px;
            }

            .dashboard .sidebar-submenu {
                display: none;
            }

            .dashboard .sidebar-submenu.open-submenu {
                display: block;
            }

            .dashboard .sidebar-submenu-list {
                padding: 5px 0;
            }

            .dashboard .sidebar-submenu-list__item {
                margin-bottom: 6px;
            }

            .dashboard .sidebar-submenu-list__item.active > a {
                color: hsl(var(--base));
                background-color: hsl(var(--base)/0.06);
            }

            .dashboard .sidebar-submenu-list__link {
                padding: 12px 15px;
                display: block;
                color: hsl(var(--body-color));
                color: #6b717e;
                font-weight: 500;
                margin-left: 20px;
                border-radius: 5px;
                position: relative;
                padding-left: 25px;
            }

            .dashboard .sidebar-submenu-list__link::before {
                left: 10px;
                width: 10px;
                height: 10px;
                background-color: transparent;
                border: 1px solid hsl(var(--black)/0.4);
                border-radius: 50%;
            }

            .dashboard .sidebar-submenu-list__link:hover {
                background-color: hsl(var(--base)/0.04);
            }

            .dashboard .sidebar-submenu-list__link .icon {
                margin-right: 8px;
                text-align: center;
                border-radius: 4px;
            }
        </style>
@endpush