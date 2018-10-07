
namespace models;

class <?=$mname?> extends Model
{   
    /**
     * $protected $tableName 对应的表
     * $protected $fillable  白名单
     */
    protected $tableName = '<?=$table?>';
    protected $fillable = ['<?=$fillable?>'];
}