<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model;
class ResourceModel extends Model {
    use SoftDeletes;
    /**
     * 资源模型
     * 定义关联数据表
     * @var string
     */
    protected $table = 'ys_resource';
    /**
     * 应该被调整为日期的属性
     * @var array
     */
    protected $dates = ['deleted_at'];
}
