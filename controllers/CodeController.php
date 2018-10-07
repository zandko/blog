<?php

namespace controllers;

use libs\Db;
use PDO;

class CodeController
{
    public function make()
    {
        $table = $_GET['name'];
        $tableName = substr($_GET['name'], 0, strrchr($_GET['name'], 's'));

        var_dump($tableName);
        die;
        $db = Db::getInstance();

        $stmt = $db->prepare("SHOW FULL FIELDS FROM $table");
        $stmt->execute();
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $fillable = [];
        foreach ($fields as $v) {
            if ($v['Field'] == 'id' || $v['Field'] == 'created_at') {
                continue;
            }

            $fillable[] = $v['Field'];
        }

        $fillable = implode("','", $fillable);

        $cname = ucfirst($tableName) . 'Controller';
        $mname = ucfirst($tableName);

        ob_start();
        include ROOT . "templates/controller.php";
        $str = ob_get_clean();
        file_put_contents(ROOT . "controllers/" . $cname . ".php", "<?php \r\n" . $str);

        ob_start();
        include ROOT . "templates/model.php";
        $str = ob_get_clean();
        file_put_contents(ROOT . "models/" . $mname . ".php", "<?php \r\n" . $str);
    }
}
