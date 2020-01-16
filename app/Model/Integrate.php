<?php
namespace App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model;
class Integrate extends Model {
    use SoftDeletes;
    /**
     * 集成号
     * 关联到模型的数据表
     * @var string
     */
    protected $table = 'ys_integrate';
    /**
     * 应该被调整为日期的属性
     * @var array
     */
    protected $dates = ['deleted_at'];
}