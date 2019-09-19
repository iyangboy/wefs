<?php

namespace App\Admin\Controllers;

use App\Models\Topic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;

class TopicsController extends Controller
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
            ->header('话题列表')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
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
     * @param mixed $id
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Topic);

        $grid->id('Id')->sortable();
        $grid->title('标题');
        $grid->user_id('用户')->display(function() {
            return '<a href="'.route('admin.users.show', $this->user_id).'" target="_blank">'.$this->user->name.'</a>';
        });
        $grid->category()->name('分类');
        $grid->reply_count('回复数');
        $grid->view_count('查看数')->sortable();
        $grid->created_at('创建时间')->sortable();
        $grid->updated_at('更新时间');


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Topic::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->body('Body')->unescape();
        $show->user_id('User id');
        $show->category_id('Category id');
        $show->reply_count('Reply count');
        $show->view_count('View count');
        $show->last_reply_user_id('Last reply user id');
        $show->order('Order');
        $show->excerpt('Excerpt');
        $show->slug('Slug');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Topic);

        $form->text('title', 'Title');
        // $form->textarea('body', 'Body');
        $form->simditor('body', 'Body');
        $form->number('user_id', 'User id');
        $form->number('category_id', 'Category id');
        $form->number('reply_count', 'Reply count');
        $form->number('view_count', 'View count');
        $form->number('last_reply_user_id', 'Last reply user id');
        $form->number('order', 'Order');
        $form->textarea('excerpt', 'Excerpt');
        $form->text('slug', 'Slug');

        return $form;
    }

    // 上传图片
    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', 'admin_'.\Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}
