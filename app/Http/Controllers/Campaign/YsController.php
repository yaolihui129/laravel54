<?php
namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Model\ResourceModel;
use App\Model\VersionModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Excel;

class YSController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return string
	 */
	public function index()
	{
        $user=Auth::user();
        $pages=array();
        if(!empty($user)){
            $pages["login"]="1";
        }
        return view("campaign.case05.index")->with($pages);
	}


    public function getYSResource(Request $request){
        /**
         * 初始换开始时间和结束时间
         */
        $time=time();
        $version    = $request->input('version');
        $integrate  = $request->input('integrate');
        $startTime  = $request->input('startTime');
        if(!$startTime){
            $startTime = date('Y-m-d',$time-7*24*60*60);
        }
        $endTime    = $request->input('endTime');
        if(!$endTime){
            $endTime = date('Y-m-d',$time+24*60*60);
        }

        /**
         * 0-all：整体进度
         */
        $all        = $this->getResInfo('0',$version,$integrate,$startTime,$endTime);
        /**
         * 1-api
         */
        $api        = $this->getResInfo('1',$version,$integrate,$startTime,$endTime);
        /**
         * 2-bug
         */
        $bug        = $this->getResInfo('2',$version,$integrate,$startTime,$endTime);
        /**
         * 3-listLeft
         */
        $ListLeft   = $this->getResInfo('3',$version,$integrate,$startTime,$endTime);
        /**
         * 4-listRight
         */
        $ListRight  = $this->getResInfo('4',$version,$integrate,$startTime,$endTime);
        /**
         * 5-pmdLeft
         */
        $pmdLeft    = $this->getResInfo('5',$version,$integrate,$startTime,$endTime);
        /**
         * 6-pmdRight
         */
        $pmdRight   = $this->getResInfo('6',$version,$integrate,$startTime,$endTime);
        /**
         * 7-story故事点
         */
        $story      = $this->getResInfo('7',$version,$integrate,$startTime,$endTime);
        /**
         * 8-water水球信息
         */
        $water      = $this->getResInfo('8',$version,$integrate,$startTime,$endTime);
        
        /**倒计时信息
         * 发布时间-当天折算成天数
         * 如果大于等于0直接输出
         * 如果小于0 输入当前版本已上线
         */
        $edition= VersionModel::find($version);
        $edition=$edition->IssueDate;
        //计算发布时间到今天的差值
        $edition=strtotime($edition)-$time;
        if($edition > 0){
            //将差值四舍五入保留一位小数
            $edition=round($edition/(24*60*60),1);
            $edition='倒计时：'.$edition.'天';
        }else{
            $edition='版本已上线'; 
        }
        $arr=array();
        $arr['success'] = 1;
        $arr['message']='ok';
        $arr['data'] = [
            'all'           => $all,
            'api'           => $api,
            'bug'           => $bug,
            'newListLeft'   => $ListLeft,
            'newListRight'  => $ListRight,
            'pmdLeft'       => $pmdLeft,
            'pmdRight'      => $pmdRight,
            'story'         => $story,
            'water'         => $water,
            'edition'       => $edition
        ];
        return response()->json($arr);
    }


    function getResInfo($type,$version,$integrate,$startTime,$endTime)
    {
        //通过ORM模型从数据库中查询
        $res = ResourceModel::where('intVersionID','=',$version)
            ->where('intIntegrateID','=',$integrate)
            ->where('enumType','=',$type)
            ->whereBetween('resDate',[$startTime,$endTime])
            ->orderBy('created_at','desc')->first();
        if(!$res){
            return '{"date":{}}';
        }else{
            if($res->textJson){
                return $res->textJson;
            }else{
                return '{"date":{}}'; 
            } 
        }
    }
    






}
