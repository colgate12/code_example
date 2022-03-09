<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;

class UserController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return 'Пользователи';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', 'ID')->sortable();
        $grid->column('first_name', 'Имя');
        $grid->column('last_name', 'Фамилия');
        $grid->column('email', 'Email');
        $grid->column('created_at', trans('admin.created_at'));

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $form = new Form(new User());

        $form->text('first_name', 'Имя')->rules('required');
        $form->text('last_name', 'Фамилия')->rules('required');
        $form->text('email', 'Email')->rules('required');

        return $form;
    }
}
