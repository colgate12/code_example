<?php

namespace App\Admin\Controllers;

use App\Models\Products;
use App\Models\ProductsCategories;
use App\Models\Sizes;
use App\Services\ProductsService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;

class ProductsController extends AdminController
{
    /**
     * @var ProductsService
     */
    private $service;

    /**
     * ProductsController constructor.
     *
     * @param ProductsService $service
     */
    public function __construct(ProductsService $service)
    {
        $this->service = $service;
    }

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Товары';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Products::class, function (Grid $grid) {
            $grid->model()->orderBy('created_at', 'desc');
            $grid->column('id', __('ID'))->sortable()->filter();

            $grid->column('image', 'Изображение')
                ->display(function ($image) {
                    /** @var Products $this */
                    return '<img src="'.$this->getFirstMediaUrl('image').'" style="max-width:96px;width:100%">';
                })
                ->style('min-width:140px')->sortable();

            $grid->category_id('Категория')->display(function($categoryId) {
                return ProductsCategories::find($categoryId)->name;
            });
            $grid->quality('Качество')->display(function ($type) {
                return Products::QUALITY_TYPES[$type];
            })->sortable();
            $states = [
                'on'  => ['value' => 1, 'text' => 'Да', 'color' => 'default'],
                'off' => ['value' => 0, 'text' => 'Нет', 'color' => 'primary'],
            ];
            $grid->hidden('Скрыть')->switch($states);
            $grid->column('price', 'Цена');
            $grid->column('created_at', __('Created at'));

            $grid->filter(function ($filter) {
                $filter->between('id');
                $filter->equal('quality', 'Качество')->select(Products::QUALITY_TYPES);
            });
        });
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update($id)
    {
        $request = request();
        if (true === in_array($request->hidden, ['on', 'off'])) {
            return $this->service->hiddenProduct($id, $request);
        }

        $result = $this->form()->ignore(['image', 'imageList'])->update($id);
        $this->service->uploadsImage($id, $request);

        return $result;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Products::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Products());

        $form->display('id', __('ID'));
        $form->image('image', 'Изображение');
        $form->multipleImage('imageList', 'Изображенния');
        $form->text('name', 'Название')->rules('required');
        $form->text('price', 'Цена')->rules('required');
        $form->text('description', 'Описание');

        $form->select('quality', 'Качество')->options(Products::QUALITY_TYPES)->rules('required');
        $form->select('category_id', 'Категория')->options(ProductsCategories::whereHidden(0)->pluck('name', 'id'))->rules('required');
        $form->multipleSelect('sizes', 'Размеры')->options(Sizes::all()->pluck('name', 'id'));

        $form->display('created_at', __('Created At'));
        return $form;
    }

}
