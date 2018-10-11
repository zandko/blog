<?php

namespace models;

class Role extends Model
{
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = 'role';
    protected $fillable = ['role_name'];

    protected function _after_write()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];

        $sql = "DELETE FROM role_privilege WHERE role_id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $id,
        ]);

        $sql = "INSERT INTO role_privilege(role_id,pri_id) VALUES(?,?)";
        $stmt = $this->_db->prepare($sql);

        foreach ($_POST['pri_id'] as $v) {
            $stmt->execute([
                $id,
                $v,
            ]);
        }
    }

    protected function _before_delete()
    {

        $sql = "DELETE FROM role_privilege WHERE role_id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $_GET['id'],
        ]);
    }

    public function getPriIds($role_id)
    {
        $sql = "SELECT pri_id FROM role_privilege WHERE role_id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([
            $role_id,
        ]);

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $_ret = [];
        foreach ($data as $v) {
            $_ret[] = $v['pri_id'];
        }

        return $_ret;

    }
}
