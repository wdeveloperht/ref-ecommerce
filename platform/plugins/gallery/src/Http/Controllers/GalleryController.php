<?php

namespace Botble\Gallery\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Gallery\Forms\GalleryForm;
use Botble\Gallery\Http\Requests\GalleryRequest;
use Botble\Gallery\Models\Gallery;
use Botble\Gallery\Models\GalleryItem;
use Botble\Gallery\Tables\GalleryTable;
use Illuminate\Http\Request;

class GalleryController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/gallery::gallery.menu'), route('gallery.index'));
    }

    public function index(GalleryTable $dataTable)
    {
        $this->pageTitle(trans('plugins/gallery::gallery.menu'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/gallery::gallery.create'));

        return GalleryForm::create()
            ->removeMetaBox('gallery-items')
            ->renderForm();
    }

    public function store(GalleryRequest $request)
    {
        $form = GalleryForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('gallery.index')
            ->setNextRoute('gallery.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Gallery $gallery)
    {
        Assets::addScripts('sortable')
            ->addScriptsDirectly('vendor/core/plugins/gallery/js/gallery-admin.js');

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $gallery->name]));

        return GalleryForm::createFromModel($gallery)
            ->renderForm();
    }

    public function update(Gallery $gallery, GalleryRequest $request)
    {
        GalleryForm::createFromModel($gallery)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('gallery.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Gallery $gallery)
    {
        return DeleteResourceAction::make($gallery);
    }

    public function postSorting(Request $request)
    {
        foreach ($request->input('items', []) as $key => $id) {
            GalleryItem::query()->where('id', $id)->update(['order' => ($key + 1)]);
        }

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/gallery::gallery.update_slide_position_success'));
    }
}
