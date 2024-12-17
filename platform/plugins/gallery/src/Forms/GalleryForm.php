<?php

namespace Botble\Gallery\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Gallery\Http\Requests\GalleryRequest;
use Botble\Gallery\Models\Gallery;
use Botble\Gallery\Tables\GalleryItemTable;
use Botble\Table\TableBuilder;

class GalleryForm extends FormAbstract
{
    public function __construct(protected TableBuilder $tableBuilder)
    {
        parent::__construct();
    }

    public function setup(): void
    {
        $this
            ->model(Gallery::class)
            ->setValidatorClass(GalleryRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add(
                'key',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/gallery::gallery.key'))
                ->required()
                ->maxLength(120)
            )
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('image',
                MediaImageField::class,
                MediaImageFieldOption::make()
//                ->required()
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status')
            ->when($this->model->id, function (): void {
                $this->addMetaBoxes([
                    'gallery-items' => [
                        'title' => trans('plugins/gallery::gallery.gallery_items'),
                        'content' => $this->tableBuilder->create(GalleryItemTable::class)
                            ->setAjaxUrl(route(
                                'gallery-item.index',
                                $this->getModel()->id ?: 0
                            ))
                            ->renderTable([
                                'gallery_id' => $this->getModel()->getKey(),
                            ]),
                        'header_actions' => view('plugins/gallery::partials.header-actions', [
                            'gallery' => $this->getModel(),
                        ])->render(),
                        'has_table' => true,
                    ],
                ]);
            });
    }
}
