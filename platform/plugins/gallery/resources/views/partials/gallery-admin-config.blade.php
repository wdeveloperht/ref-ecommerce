{{-- Deprecated --}}
<div class="mb-3">
    <label class="form-label">{{ trans('plugins/gallery::gallery.select_gallery') }}</label>
    {!! Form::customSelect('key', $sliders, Arr::get($attributes, 'key')) !!}
</div>
