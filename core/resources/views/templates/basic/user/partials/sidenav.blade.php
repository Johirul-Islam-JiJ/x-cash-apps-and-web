<div class="sidebar-menu">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-xl-none d-block"><i class="fas fa-times"></i></span>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item">
                <a class="sidebar-menu-list__link" href="{{ route('user.home') }}">
                    <span class="icon"> <i class="fas fa-th-large"></i></span>
                    <span class="text">@lang('Dashboard')</span>
                </a>
            </li>

            @if (module('add_money', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"> <i class="fas fa-money-check-alt"></i></span>
                        <span class="text">@lang('Add Money')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <li><a href="{{ route('user.deposit') }}">@lang('Add Money')</a></li>
                            <li><a href="{{ route('user.deposit.history') }}">@lang('Add Money History')</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (module('money_out', $module)->status || module('make_payment', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="far fa-money-bill-alt"></i></span>
                        <span class="text">@lang('Money Discharge')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            @if (module('money_out', $module)->status)
                                <li><a href="{{ route('user.money.out') }}">@lang('Money Out')</a></li>
                            @endif
                            @if (module('make_payment', $module)->status)
                                <li><a href="{{ route('user.payment') }}">@lang('Make Payment')</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            <li class="sidebar-menu-list__item">
                <a class="sidebar-menu-list__link" href="{{ route('user.currency.exchange.index') }}">
                    <span class="icon"> <i class="las la-exchange-alt"></i></span>
                    <span class="text">@lang('Exchange')</span>
                </a>
            </li>

            <li class="sidebar-menu-list__item">
                <a class="sidebar-menu-list__link" href="{{ route('user.exchange.money') }}">
                    <span class="icon"> <i class="las la-sync"></i></span>
                    <span class="text">@lang('Currency Converter')</span>
                </a>
            </li>

            @if (module('transfer_money', $module)->status)
                <li>
                    <a href="{{ route('user.transfer') }}" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-exchange-alt"></i></span>
                        <span class="text">@lang('Transfer')</span>
                    </a>
                </li>
            @endif

            @if (module('request_money', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-wallet"></i></span>
                        <span class="@lang('Request Money')" class="text">@lang('Request Money')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <li><a href="{{ route('user.request.money') }}">@lang('Request Money')</a></li>
                            <li><a href="{{ route('user.requests') }}">@lang('Requests to me')</a></li>
                            <li><a href="{{ route('user.request.money.history') }}">@lang('My Requested History')</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (module('create_voucher', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-ticket-alt"></i></span>
                        <span class="text">@lang('Voucher')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <li><a href="{{ route('user.voucher.list') }}">@lang('My Vouchers')</a></li>
                            <li><a href="{{ route('user.voucher.redeem') }}">@lang('Voucher Redeem')</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (module('withdraw_money', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown">
                    <a href="#" class="sidebar-menu-list__link">
                        <span class="icon"><i class="fas fa-credit-card"></i></span>
                        <span class="text">@lang('Withdrawals')</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <li><a href="{{ route('user.withdraw') }}">@lang('Withdraw Money')</a></li>
                            <li><a href="{{ route('user.withdraw.methods') }}">@lang('Withdraw methods')</a></li>
                            <li><a href="{{ route('user.withdraw.history') }}">@lang('Withdraw History')</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            <li>
                <a class="sidebar-menu-list__link" href="{{ route('user.transactions') }}">
                    <span class="icon"><i class="fas fa-poll-h"></i></span>
                    <span class="text">@lang('Transactions')</span>
                </a>
            </li>
        </ul>
        <!-- ========= Sidebar Menu End ================ -->
    </div>
</div>

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.money').on('click', function() {
                var url = "{{ route('user.check.insight') }}";
                var day = $(this).data('day');
                var text = $(this).text();
                var data = {
                    day: day
                }
                $.get(url, data, function(response) {
                    if (response.error) {
                        notify('error', response.error)
                        return false;
                    }
                    var moneyIn = response.totalMoneyIn;
                    var moneyOut = response.totalMoneyOut;
                    var curSym = '{{ $general->cur_sym }}';
                    var curTxt = '{{ $general->cur_text }}';

                    $('.money-in').text(curSym + moneyIn.toFixed(2) + ' ' + curTxt);
                    $('.money-out').text(curSym + moneyOut.toFixed(2) + ' ' + curTxt);
                    $('.last-time').text('( ' + text.toLowerCase() + ' )');
                    $('#latestAcvitiesButton').text(text);
                });
            });
        })(jQuery);
    </script>
@endpush

@push('script')
    <script>
        $(".has-dropdown > a").click(function() {
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

        $(".body__bar-icon").on("click", function() {
            $(".sidebar-menu").toggleClass("show-sidebar");
            $(".sidebar-overlay").addClass("show");
        });
        $(".sidebar-menu__close, .sidebar-overlay").on("click", function() {
            $(".sidebar-menu").removeClass("show-sidebar");
            $(".sidebar-overlay").removeClass("show");
        });
    </script>
@endpush

@push('style')
    <style>
        .sidebar-menu-list {
            margin-top: 32px;
        }

        .sidebar-menu {
            height: calc(100vh - 76px);
            background-color: #f4f4f4;
            overflow-y: auto;
            z-index: 999;
            transition: 0.2s linear;
            width: 300px;
            border-radius: 0;
            position: fixed;
            left: 0;
            top: 76px;
            padding: 0;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 3px;
            height: 3px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background-color: hsl(var(--black)/0.15);
        }

        .sidebar-menu__inner {
            width: 100%;
        }

        .sidebar-menu.show-sidebar {
            transform: translateX(0);
        }

        @media screen and (max-width: 1199px) {
            .sidebar-menu {
                transform: translateX(-100%);
                z-index: 9992;
                border-radius: 0;
                height: 100vh;
                top: 0;
            }
        }

        .sidebar-menu__close {
            font-size: 1.25rem;
            transition: 0.2s linear;
            cursor: pointer;
            text-align: right;
            margin-right: 20px;
        }

        .sidebar-menu__close:active {
            top: 14px;
        }

        .sidebar-menu__close:hover,
        .sidebar-menu__close:focus {
            background-color: hsl(var(--white));
            border-color: hsl(var(--white));
            color: hsl(var(--base));
        }

        .sidebar-menu .menu-title {
            letter-spacing: 0.9px;
            padding: 12px 15px;
            color: #9da9b5;
            font-weight: 600;
        }

        .sidebar-menu hr {
            opacity: 0.15;
        }

        .sidebar-menu-list__item {
            margin-bottom: 6px;
            position: relative;
        }

        .sidebar-menu-list__item:last-child .sidebar-menu-list__link {
            border-bottom: 0;
        }

        .sidebar-menu-list__item.active>a {
            background-color: white;
            color: hsl(var(--base));
        }

        .sidebar-menu-list__item.has-dropdown.active>a {
            color: hsl(var(--base));
        }

        .sidebar-menu-list__item.has-dropdown.active>a:after {
            transform: rotate(90deg);
            right: 18px;
            color: hsl(var(--base));
        }

        .sidebar-menu-list__item.has-dropdown>a:after {
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

        .sidebar-menu-list__link {
            display: inline-block;
            text-decoration: none;
            position: relative;
            padding: 12px 24px;
            width: 100%;
            color: #6b717e;
            font-weight: 500;
        }

        .sidebar-menu-list__link:hover {
            background-color: hsl(var(--base)/0.06);
        }

        .sidebar-menu-list__link.active {
            color: hsl(var(--base));
        }

        .sidebar-menu-list__link .icon {
            margin-right: 8px;
            text-align: center;
            border-radius: 4px;
        }

        .sidebar-submenu {
            display: none;
        }

        .sidebar-submenu.open-submenu {
            display: block;
        }

        .sidebar-submenu-list {
            padding: 5px 0;
        }

        .sidebar-submenu-list__item {
            margin-bottom: 6px;
        }

        .sidebar-submenu-list__item.active>a {
            color: hsl(var(--base));
            background-color: hsl(var(--base)/0.06);
        }

        .sidebar-submenu-list__link {
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

        .sidebar-submenu-list__link::before {
            left: 10px;
            width: 10px;
            height: 10px;
            background-color: transparent;
            border: 1px solid hsl(var(--black)/0.4);
            border-radius: 50%;
        }

        .sidebar-submenu-list__link:hover {
            background-color: hsl(var(--base)/0.04);
        }

        .sidebar-submenu-list__link .icon {
            margin-right: 8px;
            text-align: center;
            border-radius: 4px;
        }
    </style>
@endpush
