<?php

namespace Botble\Gallery\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Gallery\Models\GalleryItem;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;

class GalleryItemTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(GalleryItem::class)
            ->setView('plugins/gallery::items')
            ->setDom($this->simpleDom())
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make(),
                FormattedColumn::make('title')
                    ->title(trans('core/base::tables.title'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        $name = BaseHelper::clean($item->title);

                        if (! $this->hasPermission('gallery-item.edit')) {
                            return $name;
                        }

                        return $name ? Html::link(route('gallery-item.edit', $item->getKey()), $name, [
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#gallery-item-modal',
                        ]) : '&mdash;';
                }),
                Column::make('order')
                    ->title(trans('core/base::tables.order'))
                    ->className('text-start order-column'),
                CreatedAtColumn::make(),
            ])
            ->addActions([
                EditAction::make()
                    ->route('gallery-item.edit')
                    ->attributes([
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#gallery-item-modal',
                    ])
                    ->permission('gallery-item.edit'),
                DeleteAction::make()
                    ->route('gallery-item.destroy')
                    ->permission('gallery-item.destroy'),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'title',
                        'image',
                        'order',
                        'created_at',
                    ])
                    ->oldest('order')
                    ->where('gallery_id', request()->route()->parameter('id'));
            });
    }
}
