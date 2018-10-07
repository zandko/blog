
namespace controllers;

use models\<?=$mname?>;

class <?=$cname?>
{
    /**
     * 显示数据列表页
     *
     * @access public
     */
    public function index()
    {
        $model = new <?=$mname?>;
        $data = $model->findAll();
        view('admin.<?=$tableName?>.index',$data);
    }

    /**
     * 显示数据的添加页
     *
     * @access public
     */
    public function create()
    {
        view('admin.<?=$tableName?>.create');
    }

    /**
     * 处理要添加的数据
     *
     * @access public
     */
    public function insert()
    {
        $model = new <?=$mname?>;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin/<?=$tableName?>/index');
    }

    /**
     * 显示数据的修改页
     *
     * @access public
     */
    public function edit()
    {
        $model = new <?=$mname?>;
        $data = $model->findOne($_GET['id']);
        view('admin.<?=$tableName?>.edit',[
            'data' => $data,
        ]);
    }

    /**
     * 处理要修改的数据
     *
     * @access public
     */
     public function update()
     {
         $model = new <?=$mname?>;
         $model->fill($_POST);
         $model->update($_GET['id']);
         redirect('/admin/<?=$tableName?>/index');
     }

    /**
     * 处理要删除的数据
     *
     * @access public
     */
     public function delete()
     {
         $model = new <?=$mname?>;
         $model->delete($_GET['id']);
         redirect('/admin/<?=$tableName?>/index');
     }
}