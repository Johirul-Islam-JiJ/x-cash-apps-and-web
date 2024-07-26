@php
    $content = @getContent('why_choose_us.content', true)->data_values;
    $elements = @getContent('why_choose_us.element', orderById:true);
@endphp

<!-- <img src="{{ getImage('assets/images/frontend/why_choose_us/' .@$element->data_values->icon, '65x65') }}"
                                alt="@lang('image')"> -->

<section class="pt-100 pb-100 choose-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="section-header wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.3s">
                    <span class="section-subtitle border-left">Why Choose</span>
                    <h2 class="section-title">{{ __(@$content->heading) }}</h2>
                    <p class="mt-3">{{ __(@$content->subheading) }}</p>
                </div>

                <ul class="choose-list">
                    @foreach($elements as $element)
                    <li class="choose-item">
                        <span class="choose-list-icon">
                            <i class="las la-check-circle"></i>
                        </span>
                        <span class="choose-list-text">
                            {{ __(@$element->data_values->title) }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-6">
                <div class="choose-thumb">
                    <img src="{{ asset('assets/global/images/whychoose.png') }}" alt="">
                </div>
            </div>

        </div>
    </div>
</section>
