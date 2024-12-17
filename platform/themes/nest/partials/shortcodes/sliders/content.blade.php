<div class="slider-content">
    @if ($slider->title)
        <h1 class="display-2 mb-40">
            {!! BaseHelper::clean($slider->title) !!}
        </h1>
    @endif
    @if ($slider->description)
        <p class="mb-65">{!! BaseHelper::clean($slider->description) !!}</p>
    @endif

    @if ($slider->link && !($shortcode->show_newsletter_form == 'yes' && is_plugin_active('newsletter')))
        <a href="{{ url($slider->link) }}" class="btn btn-xs">
    @endif
        {{ trans('plugins/simple-slider::simple-slider.more') }} <i class="fi-rs-arrow-small-right"></i>
    @if ($slider->link && !($shortcode->show_newsletter_form == 'yes' && is_plugin_active('newsletter')))
        </a>
    @endif
    @if ($shortcode->show_newsletter_form == 'yes' && is_plugin_active('newsletter'))
        <div class="newsletter">
            {!! Theme::partial('newsletter-form') !!}
        </div>
    @endif
</div>
