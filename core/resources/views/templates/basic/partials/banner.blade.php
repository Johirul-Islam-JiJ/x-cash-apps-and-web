@php
    $banner = @getContent('banner.content', true)->data_values;
@endphp

<!-- style="background-image: url('{{ getImage('assets/images/frontend/banner/' .@$banner->background_image, '1920x1280') }}')" -->

<section class="hero bg_img">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <div class="hero__subtitle d-inline wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.3s">
                    {{ __(@$banner->title) }}
                </div>
                <h2 class="hero__title wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.5s">
                    {{ __(str_replace('&amp;', '&', @$banner->heading)) }}
                </h2>
                <p class="hero__des mt-3 wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.7s">
                    {{ __(@$banner->subheading) }}
                </p>
                <div class="btn--group wow fadeInUp" data-wow-duration="0.3" data-wow-delay="0.9s">
                    <a href="{{ @$banner->button_link }}" class="btn btn--base">{{ __(@$banner->button_name) }}</a>
                    <a href="{{ @$banner->video_link }}" data-rel="lightcase:myCollection" class="video-btn">
                        <span class="icon"><i class="las la-play"></i></span>
                        <span class="text--base">{{ @__($banner->video_button_name) }}</span>
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="banner-thumb">
                    <img class="fit-image" src="{{ asset('assets/global/images/banner.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- hero section end -->
