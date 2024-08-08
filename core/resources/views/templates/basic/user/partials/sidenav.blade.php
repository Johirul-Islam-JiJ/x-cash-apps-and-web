
<div class="sidebar-menu">
    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-lg-none d-block"><i class="fas fa-times"></i></span>
        <div class="sidebar-logo">
            <a href="index.html" class="sidebar-logo__link"><img src="assets/images/logo/logo.png" alt=""></a>
        </div>

        <ul class="sidebar-menu-list">
        
            <li class="sidebar-menu-list__item"><a class="sidebar-menu-list__link" href="{{ route('user.home') }}">@lang('Dashboard')</a></li>

            @if (module('add_money', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown"><a href="#0">@lang('Add Money')</a>
                    <div class="sidebar-submenu">
                        <ul class="sidebar-submenu-list">
                            <li><a href="{{ route('user.deposit') }}">@lang('Add Money')</a></li>
                            <li><a href="{{ route('user.deposit.history') }}">@lang('Add Money History')</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (module('money_out', $module)->status || module('make_payment', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown"><a href="#0">@lang('Money Discharge')</a>
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

            @if (module('transfer_money', $module)->status)
                <li><a href="{{ route('user.transfer') }}">@lang('Transfer')</a></li>
            @endif

            @if (module('request_money', $module)->status)
                <li class="sidebar-menu-list__item has-dropdown"><a href="#0">@lang('Request Money')</a>
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
                    <a href="#0">@lang('Voucher')</a>
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
                    <a href="#0">@lang('Withdrawals')</a>
                <div class="sidebar-submenu">
                    <ul class="sidebar-submenu-list">
                        <li><a href="{{ route('user.withdraw') }}">@lang('Withdraw Money')</a></li>
                        <li><a href="{{ route('user.withdraw.methods') }}">@lang('Withdraw methods')</a></li>
                        <li><a href="{{ route('user.withdraw.history') }}">@lang('Withdraw History')</a></li>
                    </ul>
                </div>
                </li>
            @endif 

            <li><a href="{{ route('user.transactions') }}">@lang('Transactions')</a></li>
        </ul>
        <!-- ========= Sidebar Menu End ================ -->
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
        $("body__bar-icon").on("click", function () {
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
            
            .sidebar-menu {
                height: calc(100vh - 78px);
                background-color: #f2f2f2;
                overflow-y: auto;
                z-index: 999;
                transition: 0.2s linear;
                width: 300px;
                border-right: 1px solid rgb(0 0 0 / 0.1);
                border-radius: 0;
                position: fixed;
                left: 0;
                top: 78px;
                align-items: start;
                flex-direction: row;
            }

            .sidebar-menu::-webkit-scrollbar {
                width: 3px;
                height: 3px;
            }

            .sidebar-menu::-webkit-scrollbar-thumb {
                background-color: hsl(var(--black)/0.15);
            }

            .sidebar-menu__inner {
                padding: 0 24px;
                width: 100%;
            }

            .sidebar-menu.show-sidebar {
                transform: translateX(0);
            }

            @media screen and (max-width: 991px) {
                .sidebar-menu {
                    transform: translateX(-100%);
                    z-index: 9992;
                    border-radius: 0;
                }
            }

            .sidebar-menu__close {
                position: absolute;
                top: 8px;
                right: 16px;
                color: hsl(var(--body-color));
                font-size: 1.25rem;
                transition: 0.2s linear;
                cursor: pointer;
                z-index: 9;
            }

            .sidebar-menu__close:active {
                top: 14px;
            }

            .sidebar-menu__close:hover, .sidebar-menu__close:focus {
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

            .sidebar-menu-list {
                margin-top: 40px;
            }

            .sidebar-menu-list__item {
                margin-bottom: 6px;
                position: relative;
            }

            .sidebar-menu-list__item:last-child .sidebar-menu-list__link {
                border-bottom: 0;
            }

            .sidebar-menu-list__item.active > a {
                background-color: hsl(var(--base)/0.1);
                color: hsl(var(--base));
            }

            .sidebar-menu-list__item.has-dropdown.active > a {
                color: hsl(var(--base));
            }

            .sidebar-menu-list__item.has-dropdown.active > a:after {
                transform: rotate(90deg);
                right: 18px;
                color: hsl(var(--base));
            }

            .sidebar-menu-list__item.has-dropdown > a:after {
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
                padding: 12px 15px;
                width: 100%;
                color: #6b717e;
                font-weight: 500;
                border-radius: 5px;
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

            .sidebar-submenu-list__item.active > a {
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