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

    public function login($username, $password)
    {
        $stmt = $this->_db->prepare("SELECT * FROM admin WHERE username=? AND password=?");
        $stmt->execute([
            $username,
            $password,
        ]);
        $adminUser = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($adminUser) {
            $_SESSION['id'] = $adminUser['id'];
            $_SESSION['username'] = $adminUser['username'];

            $stmt = $this->_db->prepare("SELECT COUNT(*) FROM admin_role WHERE role_id = 1 AND admin_id = ?");
            $stmt->execute([
                $_SESSION['id'],
            ]);

            $root = $stmt->fetch(\PDO::FETCH_COLUMN);
            if ($root > 0) {
                $_SESSION['root'] = true;
            } else {
                $_SESSION['url_path'] = $this->getUrlPath($_SESSION['id']);
            }
            return $adminUser;
        } else {
            return false;
        }
    }

    public function getUrlPath($adminId)
    {
        $stmt = $this->_db->prepare("SELECT c.url_path
                                FROM admin_role a
                                LEFT JOIN role_privilege b ON a.role_id = b.role_id
                                LEFT JOIN privilege c ON b.pri_id = c.id
                                WHERE a.admin_id=? AND c.url_path != ''");
        $stmt->execute([
            $adminId,
        ]);

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $_ret = [];

        foreach ($data as $v) {
            if (false === strpos($v['url_path'], ',')) {
                $_ret[] = $v['url_path'];
            } else {
                $_tt = explode(',', $v['url_path']);
                $_ret[] = array_merge($_ret, $_tt);
            }
        }

        return $_ret;
    }
}
