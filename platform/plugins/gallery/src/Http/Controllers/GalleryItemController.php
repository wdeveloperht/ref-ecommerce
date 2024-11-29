<?php

namespace Botble\Gallery\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Gallery\Forms\GalleryItemForm;
use Botble\Gallery\Http\Requests\GalleryItemRequest;
use Botble\Gallery\Models\GalleryItem;
use Botble\Gallery\Tables\SimpleSliderItemTable;

class GalleryItemController extends BaseController
{
    public function index(SimpleSliderItemTable $dataTable)
    {
        return $dataTable->renderTable();
    }

    public function create()
    {
        $form = GalleryItemForm::create()
            ->setUseInlineJs(true)
            ->renderForm();

        return $this
            ->httpResponse()
            ->setData([
                'title' => trans('plugins/gallery::gallery.create_new_slide'),
                'content' => $form,
            ]);
    }

    public function store(GalleryItemRequest $request)
    {
        GalleryItemForm::create()->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id)
    {
        $simpleSliderItem = GalleryItem::query()->findOrFail($id);

        $form = GalleryItemForm::createFromModel($simpleSliderItem)
            ->setUseInlineJs(true)
            ->renderForm();

        return $this
            ->httpResponse()
            ->setData([
                'title' => trans('plugins/gallery::gallery.edit_slide', ['id' => $simpleSliderItem->getKey()]),
                'content' => $form,
            ]);
    }

    public function update(int|string $id, GalleryItemRequest $request)
    {
        $simpleSliderItem = GalleryItem::query()->findOrFail($id);

        GalleryItemForm::createFromModel($simpleSliderItem)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id)
    {
        $simpleSliderItem = GalleryItem::query()->findOrFail($id);

        return DeleteResourceAction::make($simpleSliderItem);
    }
}
