@php
    $subscribe = getContent('subscribe.content', true);
@endphp

<section class="subscribe-section">
    <div class="container">
        <div class="subscribe-content mb-4 text-center">
            <h3 class="text-white">{{ __(@$subscribe->data_values->heading) }}</h3>
        </div>
        <form action="" method="POST" class="subscription-form">
            @csrf
            <form class="search-form">
                <div class="input--group">  
                    <input type="email" name="email" class="form--control" placeholder="@lang('Enter Email')">
                    <button type="submit" class="btn btn--base rounded-0"> <span class="btn-text">@lang('Subscribe')</span> <span class="btn-icon"><i class="fas fa-paper-plane"></i></span> </button>
                </div>
            </form>
        </form>
    </div>
</section>

@push('script')
    <script>
        (function($){

            "use strict";

            var formEl = $(".subscription-form");

            formEl.on('submit', function(e){
                e.preventDefault();
                var data = formEl.serialize();

                if(!formEl.find('input[name=email]').val()){
                    return notify('error', 'Email field is required');
                }

                $.ajax({
                url:"{{ route('subscribe') }}",
                method:'post',
                data:data,

                success:function(response){
                    if(response.success){
                        formEl.find('input[name=email]').val('')
                        notify('success', response.message);
                    }else{
                        $.each(response.error, function( key, value ) {
                            notify('error', value);
                        });
                    }
                },
                error:function(error){
                        console.log(error)
                    }

                });
            });

        })(jQuery);
    </script>
@endpush




