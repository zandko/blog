<?php

namespace models;

use libs\Db;
use PDO;

/**
 * 实例化 Db 类
 */
class Model
{
    protected $_db;
    protected $tableName;
    protected $data;

    public function __construct()
    {
        $this->_db = Db::getInstance();
    }

    /**
     * 钩子函数
     *
     * @access protected
     */
    protected function _before_write()
    {}
    protected function _after_write()
    {}
    protected function _before_delete()
    {}
    protected function _after_delete()
    {}

    /**
     * 设置白名单
     *
     * @access public
     * @param mixed $data 表单数据
     * @return array
     */

    public function fill($data)
    {
        foreach ($data as $k => $v) {
            if (!in_array($k, $this->fillable)) {
                unset($data[$k]);
            }
        }

        return $this->data = $data;
    }

    /**
     * 操作数据库的添加方法
     *
     * @access public
     * @return int
     */
    public function insert()
    {
        $this->_before_write();

        $keys = [];
        $values = [];
        $token = [];

        foreach ($this->data as $k => $v) {
            $keys[] = $k;
            $values[] = $v;
            $token[] = "?";
        }

        $keys = implode(",", $keys);
        $token = implode(",", $token);
        $sql = "INSERT INTO {$this->tableName}($keys) VALUES($token)";

        $stmt = $this->_db->prepare($sql);
        $stmt->execute($values);

        $this->data['id'] = $this->_db->lastInsertId();

        $this->_after_write();
    }

    /**
     * 操作数据库的删除方法
     *
     * @access public
     * @param mixed $id 条件
     * @return 无
     */
    public function delete($id)
    {
        $this->_before_delete();

        $sql = "DELETE FROM {$this->tableName} WHERE id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([$id]);

        $this->_after_delete();
    }

    /**
     * 操作数据库的修改方法
     *
     * @access public
     * @param mixed $id 条件
     * @return 无
     */
    public function update($id)
    {
        $this->_before_write();

        $set = [];
        $values = [];
        foreach ($this->data as $k => $v) {
            $set[] = "$k=?";
            $values[] = $v;
        }

        $set = implode(",", $set);
        $values[] = $id;

        $sql = "UPDATE {$this->tableName} SET $set WHERE id = ?";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($values);

        $this->_after_write();
    }

    /**
     * 查询所有数据-带分页功能
     *
     * @access public
     * @param mixed $option 要查询的数据 条件 排序 每页显示的数量
     * @return [array,string]
     */
    public function findAll($option = [])
    {
        $_option = [
            'fields' => '*',
            'where' => '1',
            'order_by' => 'id',
            'order_way' => 'DESC',
            'per_page' => 20,
            'join' => '',
            'groupby' => '',
        ];

        if ($option) {
            $_option = array_merge($_option, $option);
        }

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset = ($page - 1) * $_option['per_page'];

        $sql = "SELECT {$_option['fields']}
                    FROM {$this->tableName} {$_option['join']}
                        WHERE {$_option['where']} {$_option['groupby']}
                        ORDER BY {$_option['order_by']} {$_option['order_way']}
                        LIMIT $offset,{$_option['per_page']}";

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT COUNT(*)
                    FROM {$this->tableName}
                        WHERE {$_option['where']}";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_COLUMN);

        $pageCount = ceil($count / $_option['per_page']);

        $prev = ($page - 1 <= 0) ? 1 : $page - 1;
        $next = ($page + 1 >= $pageCount) ? $pageCount : $page + 1;

        $pageStr = "<nav class='navigation'>";
        $pageStr .= "<ul class='cd-pagination no-space'>";

        if ($page > 0) {

            unset($_GET['page']);
            $str = "";
            if (!isset($_GET['page'])) {
                foreach ($_GET as $k => $v) {
                    $str .= "$k=$v&";
                }
            }

            if ($page != 1) {
                $pageStr .= "<li class='button'><a href='?{$str}page={$prev}'></a></li>";
            }

            $start = 1;
            $end = $pageCount;

            if ($page <= 5) {
                $start = 1;
                $end = min(7, $pageCount);
            } else if ($page >= 6 && $page <= $pageCount - 3) {
                $start = $page - 3;
                $end = $page + 2;
                $pageStr .= "<li><a href='?{$str}page=1'>1</a></li>";
                $pageStr .= "<li><a href='?{$str}page=2'>2</a></li>";
                $pageStr .= "<li><span>...</span></li>";
            } else {
                $start = $page - 3;
                $end = $pageCount;
                $pageStr .= "<li><a href='?{$str}page=1'>1</a></li>";
                $pageStr .= "<li><a href='?{$str}page=2'>2</a></li>";
                $pageStr .= "<li><span>...</span></li>";
            }

            for ($i = $start; $i <= $end; $i++) {
                $active = "";
                if ($i == $page) {
                    $active .= "class = 'current'";
                }
                $pageStr .= "<li><a href='?{$str}page={$i}' {$active}>$i</a></li>";
            }

            if ($end < $pageCount) {
                $pageStr .= "<li><span>...</span></li>";
            }

            if ($page < $pageCount) {
                $pageStr .= "<li class='button'><a href='?{$str}page={$next}'></a></li>";
            }

        } else {
            echo "暂无数据...";
        }

        $pageStr .= '</ul>';
        $pageStr .= '</nav>';

        return [
            'data' => $data,
            'page' => $pageStr,
            'count' => $count,
        ];

    }

    /**
     * 查询单条数据
     *
     * @access public
     * @param mixed $id 条件
     * @return [array]
     */
    public function findOne($id)
    {
        $stmt = $this->_db->prepare("SELECT * FROM {$this->tableName} WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tree()
    {
        $data = $this->findAll([
            'per_page' => 9999,
        ]);
        // 递归重新排序
        $ret = $this->_tree($data['data']);
        return $ret;
    }

    protected function _tree($data, $parent_id = 0, $level = 0)
    {
        // 定义一个数组保存排序好之后的数据
        static $_ret = [];
        foreach ($data as $v) {
            if ($v['parent_id'] == $parent_id) {
                // 标签它的级别
                $v['level'] = $level;
                // 挪到排序之后的数组中
                $_ret[] = $v;
                // 找 $v 的子分类
                $this->_tree($data, $v['id'], $level + 1);
            }
        }
        // 返回排序好的数组
        return $_ret;
    }

}
