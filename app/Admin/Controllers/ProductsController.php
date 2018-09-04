<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('商品列表')
            ->description('')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('添加商品')
            ->description('')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product);

        $grid->id('Id')->sortable();
        $grid->title('名称');
        $grid->on_sale('已上架')->display(function($value) {
            return $value ? '是' : '否';
        });
        $grid->price('价格');
        $grid->stock('库存');
        $grid->sold_count('售卖数');
        $grid->label('标签');
        // $grid->image('封面图')->display(function($value) {
        //     $src = config('app.url') . '/storage/' . $value;
        //     return '<img style="width:100px;height:100px;" src="' . $src . '"/>';
        // });
        
        $grid->actions(function ($actions) {
            // 不在每一行后面展示删除按钮
            $actions->disableDelete();
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                // 禁用批量删除按钮
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));

        $show->id('Id');
        $show->title('商品名称');
        $show->image('封面图片')->image();
        $show->price('商品单价');
        $show->stock('剩余库存');
        $show->label('商品标签');
        $show->on_sale('已上架')->as(function ($value) {
            return $value ? '是' : '否';
        });
        $show->sold_count('销量');
        $show->review_count('查看数');
        $show->description('商品描述');
        $show->created_at('创建时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);

        $form->text('title', '商品名称')->rules('required');
        $form->image('image', '封面图片')->rules('required|image');
        $form->radio('on_sale', '是否上架')->options(['0' => '否', '1' => '是'])->default('0');
        $form->text('price', '商品单价')->rules('required|numeric|min:0.01');
        $form->number('stock', '剩余库存')->rules('required|integer|min:0');
        $form->text('label', '商品标签');
    
        $form->editor('description', '商品描述')->rules('required');

        $form->tools(function (Form\Tools $tools) {
            // 去掉返回按钮
            // $tools->disableBackButton();
            // 去掉跳转列表按钮
            // $tools->disableListButton();
        });

        return $form;
    }
}
