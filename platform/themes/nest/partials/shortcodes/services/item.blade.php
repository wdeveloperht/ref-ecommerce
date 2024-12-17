<div class="banner-img wow animate__animated animate__fadeInUp {{ $class ?? '' }}" @if (!empty($loop)) data-wow-delay="{{ $loop->iteration * 2 / 10 }}" @endif>
    {!! ServiceManager::displayService($service->key) !!}
    <div class="banner-text">
        <h4 @if (isset($shortcode) && $shortcode->text_color) style="color: {{ $shortcode->text_color }} !important;" @endif>
            {{ $service->name }}
        </h4>
        @if($shortcode->style == 'with-subtitle')
            <h5>{{ $service->subtitle }}</h5>
        @endif
        @if($shortcode->style == 'with-description')
            <div class="description">{!! $service->description !!}</div>
        @endif
        @if ($service->slug)
            <a href="{{ $service->slug }}" @if($service->open_in_new_tab) target="_blank" @endif class="btn btn-xs">
                {{ trans('plugins/services::services.more') }} <i class="fi-rs-arrow-small-right"></i>
            </a>
        @endif
    </div>
</div>
