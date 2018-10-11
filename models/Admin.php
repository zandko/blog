<?php

namespace models;

class Admin extends Model
{
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'admin';
    protected $fillable = ['username', 'password'];

    protected function _before_write()
    {
        $this->data['password'] = md5($_POST['password']);
    }

    protected function _after_write()
    {

        $id = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];
        $sql = "DELETE FROM admin_role WHERE admin_id = ?";

        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $id,
        ]);

        $sql = "INSERT INTO admin_role(admin_id,role_id) VALUES(?,?)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $id,
            $_POST['role_id'],
        ]);
    }

    public function stop($id)
    {
        $sql = "UPDATE admin SET state='1' WHERE id=?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $id,
        ]);
    }

    public function start($id)
    {
        $sql = "UPDATE admin SET state='0' WHERE id=?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $id,
        ]);
    }
}
