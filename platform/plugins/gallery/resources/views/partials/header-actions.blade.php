<x-core::button
    tag="a"
    data-bs-toggle="modal"
    data-bs-target="#gallery-item-modal"
    :href="route('gallery-item.create', ['gallery_id' => BaseHelper::stringify($gallery->id)])"
    icon="ti ti-plus"
>
    {{ trans('plugins/gallery::gallery.add_new') }}
</x-core::button>

<x-core::button
    type="button"
    icon="ti ti-device-floppy"
    class="btn-save-sort-order"
    data-url="{{ route('gallery.sorting') }}"
    style="display: none;"
>
    {{ trans('plugins/gallery::gallery.save_sorting') }}
</x-core::button>
