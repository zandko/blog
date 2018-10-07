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
     * @access protected 
     * @param mixed $data 表单数据
     * @return array 
     */  
    protected function fill($data)
    {
        foreach ($data as $k => $v) {
            if (!in_array($data, $this->fillable)) {
                unset($data[$k]);
            }
        }

        return $this->data = $data;
    }
    
    /**  
     * 操作数据库的添加方法 
     * 
     * @access protected 
     * @return int 
     */  
    protected function insert()
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
        $sql = "INSERT INTO {$this->tableName}() VALUES($token)";

        $stmt = $this->_db->prepare($sql);
        $stmt->execute($values);

        return $this->_db->lastInsertId();

        $this->_after_write();
    }

    /**  
     * 操作数据库的删除方法 
     * 
     * @access protected 
     * @param mixed $id 条件
     * @return 无
     */  
    protected function delete($id)
    {
        $this->_before_delete();

        $sql = "DELETE FROM {$this->tableName} WHERE id = ?";
        $this->_db->execute([$id]);

        $this->_after_delete();
    }

    /**  
     * 操作数据库的修改方法 
     * 
     * @access protected 
     * @param mixed $id 条件
     * @return 无
     */  
    protected function update($id)
    {
        $this->_before_write();

        $set = [];
        $values = [];
        foreach ($this->data as $k => $v) {
            $set[] = "$k=?";
            $values = $v;
        }

        $set = implode(",", $set);
        $values = $id;

        $sql = "UPDATE {$this->tableName} SET $set WHERE $id";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute($values);

        $this->_after_delete();
    }

    /**  
     * 查询所有数据-带分页功能
     * 
     * @access protected 
     * @param mixed $option 要查询的数据 条件 排序 每页显示的数量
     * @return [array,string]
     */  
    protected function findAll($option)
    {
        $_option = [
            'fields' => '*',
            'where' => '1',
            'order_by' => 'id',
            'order_way' => 'DESC',
            'per_page' => 20,
        ];

        if ($option) {
            $_option = array_merge($_option, $option);
        }

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $offset = ($page - 1) * $_option['per_page'];

        $sql = "SELECT * FROM {$this->tableName} WHERE {$_option['fields']}  ORDER BY {$_option['order_by']} {$_option['order_way']}  LIMIT $offset,{$_option['per_page']}";

        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT COUNT(*) FROM {$this->tableName}";
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

            if ($_GET['page']) {
                foreach ($_GET as $k => $v) {
                    $str .= "$k=$v";
                }
            }

            if ($page != 1) {
                $pageStr .= "<li  class='button'><a href='?{$str}page={$prev}'></a></li>";
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
        ];

    }

    /**  
     * 查询单条数据
     * 
     * @access protected 
     * @param mixed $id 条件
     * @return [array]
     */  
    protected function findOne($id)
    {
        $stmt = $this->_db->prepare("SELECT * FROM {$this->tableName} WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
