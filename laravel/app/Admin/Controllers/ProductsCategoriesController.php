<?php

namespace App\Admin\Controllers;

use App\Models\ProductsCategories;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsCategoriesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Категории товаров';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductsCategories());
        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('id', __('ID'))->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('name', __('Название'));
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProductsCategories());

        $form->display('id', __('ID'));
        $form->text('name', 'Название')->rules('required');
        $form->select('parent_id', 'Родительская категория')->options(ProductsCategories::where(['parent_id' => null])->all()->pluck('name', 'id'));
        $form->display('created_at', __('Created At'));

        return $form;
    }
}
