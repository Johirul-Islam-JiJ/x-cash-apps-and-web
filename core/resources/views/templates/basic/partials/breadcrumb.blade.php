@php
    $content = getContent('breadcrumb.content', true)->data_values;
@endphp
<section class="inner-hero pb-100 pt-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="page-title text-center">{{ __($pageTitle) }}</h2>
                <ul class="page-breadcrumb justify-content-center">
                    <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                    <li>{{ __($pageTitle) }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>
