@extends($activeTemplate . 'layouts.user_master')

@section('content')
    <div class="custom--card">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-10">
                    <h6>@lang($pageTitle)</h6>
                </div>
                <div class="col-2 text-end">
                    <button class="trans-serach-open-btn"><i class="las la-search"></i></button>
                </div>
            </div>
            <div class="table-responsive--sm">

                <form class="transaction-top-form mb-4" action="" method="GET">
                    <div class="custom-select-search-box">
                        <input type="text" name="search" class="form--control" value="{{ request()->search }}"
                            placeholder="@lang('Search by transactions')">
                        <button type="submit" class="search-box-btn">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </form>

                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th>@lang('Gateway | Trx')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $withdraw)
                            <tr>
                                <td>
                                    <span class="fw-bold">
                                        <span class="text-primary">{{ __(@$withdraw->method->name) }}</span>
                                    </span>
                                    <br>
                                    <small>{{ $withdraw->trx }}</small>
                                </td>
                                <td>
                                    {{ showDateTime($withdraw->created_at) }} <br>
                                    {{ diffForHumans($withdraw->created_at) }}
                                </td>
                                <td>
                                    {{ showAmount($withdraw->amount * $withdraw->rate) }} - <span
                                        class="text-danger" title="@lang('charge')">{{ showAmount($withdraw->charge * $withdraw->rate) }} {{$withdraw->currency}}
                                    </span>
                                    <br>
                                    <strong title="@lang('Amount after charge')">
                                        {{ showAmount($withdraw->final_amount * $withdraw->rate) }} {{$withdraw->currency}}
                                    </strong>
                                </td>
                                <td>
                                    @php echo $withdraw->statusBadge @endphp
                                </td>
                                <td>
                                    <button class="btn btn--dark btn-sm detailBtn"
                                        data-user_data="{{ json_encode($withdraw->withdraw_information ?? []) }}"
                                        @if ($withdraw->status == 3) 
                                            data-admin_feedback="{{ $withdraw->admin_feedback }}" 
                                        @endif
                                    >
                                        <i class="fa fa-desktop"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center not-found" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($withdraws->hasPages())
                <div class="card-footer bg-transparent pt-4 pb-3">
                    {{paginateLinks($withdraws)}}
                </div>
            @endif
        </div>
    </div><!-- custom--card end -->

    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush userData mb-2">
                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    (function($) {
    "use strict";
    $('.detailBtn').on('click', function() {
        var modal = $('#detailModal');
        var userData = $(this).data('user_data');
        var html = ``;
        
        userData.forEach(element => {
            if (element.type != 'file') {
                html += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>${element.name}</span>
                    <span">${element.value ?? 'N/A'}</span>
                </li>`;
            }
        });

        if(!html){
            html += `
                <span class="text-center">
                    <span>{{ __($emptyMessage) }}</span>
                </span>`;
        }

        modal.find('.userData').html(html);

        if ($(this).data('admin_feedback') != undefined) {
            var adminFeedback = `
                <div class="my-3">
                    <strong>@lang('Admin Feedback')</strong>
                    <p>${$(this).data('admin_feedback')}</p>
                </div>
            `;
        } else {
            var adminFeedback = '';
        }

        modal.find('.feedback').html(adminFeedback);
        modal.modal('show');
    });
    })(jQuery);
</script>
@endpush
