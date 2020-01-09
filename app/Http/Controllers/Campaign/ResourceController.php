<?php
namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Model\IntegrateModel;
use App\Model\ResourceModel;
use App\Model\VersionModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResourceController extends Controller {

    /**
     * 资源数据首页
     * @param $integrate
     * @param $version
     * @param $enumType
     * @return Factory|View
     */
    public function index($integrate,$version,$enumType)
    {
        $user=Auth::user();
        $pages=array();
        if(!empty($user)){
            $pages["login"]="1";
        }
        $res = ResourceModel::where('intVersionID','=',$version)->where('intIntegrateID','=',$integrate)
            ->where('enumType','=',$enumType)->paginate(15);
        $title=[
            '整体数据（0-all）',
            '压力、静态代码、安全、接口、UI（1-Api）',
            '缺陷数据（2-Bug）',
            '流程接口（3-ListLeft）',
            '公共项目（4-ListRight）',
            '专项测试（5-pmdLeft）',
            '客户验证（6-PmdRight）',
            '故事点进度（7-Story）',
            '水球数据（8-Water）'
        ];
        $resVersion=VersionModel::find($version);
        $resIntegrate=IntegrateModel::find($integrate);
        if($resIntegrate){
            $chrIntegrateName=$resIntegrate->chrIntegrateName;
        }else{
            $chrIntegrateName='无';
        }
        return view('campaign.case05.resource.index',[
            'res'=>$res,
            'chrKey'=>$this->chrKey($enumType),
            'title'=>$title[$enumType],
            'version'=>$version,
            'chrVersionName'=>$resVersion->chrVersionName,
            'integrate'=>$integrate,
            'chrIntegrateName'=>$chrIntegrateName,
            'enumType'=>$enumType
        ])->with($pages);
    }

    /**
     * 资源数据详情维护
     * @param Request $request
     * @param int $id
     * @param $integrate
     * @param $version
     * @param $enumType
     * @return Factory|View
     */
    public function show(Request $request,$id,$integrate,$version,$enumType)
    {
        $res            = ResourceModel::find($id);
        $resVersion     = VersionModel::find($version);
        $resIntegrate   = IntegrateModel::find($integrate);
        if($resIntegrate){
            $chrIntegrateName=$resIntegrate->chrIntegrateName;
        }else{
            $chrIntegrateName='无';
        }
        if($request->isMethod('POST')){
            $data['data'] = $request->input('data');
            $res->textJson=json_encode($data);
            if($res->save()){
                return redirect('camp/resource/'.$integrate.'/'.$version.'/'.$enumType)
                    ->with('success','维护成功');
            }else{
                return redirect()->back()->with('error','维护失败');
            }
        }
        $data=json_decode($res->textJson,true);
        //处理新数据
        if(!$data){
            $data=array(
                'data'=>$this->firstArray($enumType)
            );
        }

        return view('campaign.case05.resource.show',[
            'res'=>$res,
            'data'=>$data['data'],
            'chrKey'=>$this->chrKey($enumType),
            'version'=>$version,
            'chrVersionName'=>$resVersion->chrVersionName,
            'integrate'=>$integrate,
            'chrIntegrateName'=>$chrIntegrateName,
            'enumType'=>$enumType,
        ]);
    }

   

    /**
     * @param $enumType
     * @return mixed
     */
    function chrKey($enumType){
        $arg=['all','api','bug','listLeft','listRight','pmdLeft','pmdRight','story','water'];
        return $arg[$enumType];
    }

   

    /**
     * 资源删除
     * @param $id
     * @param $integrate
     * @param $version
     * @param $enumType
     * @return RedirectResponse
     */
    public function destroy($id,$integrate,$version,$enumType)
    {

        $res =ResourceModel::find($id);
        if($res->delete()){
            return redirect('camp/resource/'.$integrate.'/'.$version.'/'.$enumType)->with('success','删除成功-'.$id);
        }else{
            return redirect('camp/resource/'.$integrate.'/'.$version.'/'.$enumType)->with('error','删除失败-'.$id);
        }
    }

    /**
     * 字段校验
     * @param $request
     */
    function check($request){
        $this->validate($request,[
//            'res.chrIntergrateKey'=>'required|min:2|max:15',
//            'res.chrIntegrateName'=>'required|min:2|max:15',
            'res.resDate'=>'required|date',
//            'res.end_at'=>'required|date',
        ],[
            'required'=>':attribute 为必填项',
//            'min'=>':attribute 长度不能低于 2 位',
//            'max'=>':attribute 长度不能超过 15 位',
//            'integer'=>':attribute 必须是整数',
            'date'=>':attribute 必须是日期',
        ],[
//            'res.chrIntergrateKey'=>'Key',
//            'res.chrIntegrateName'=>'版本号',
//            'res.start_at'=>'发版日期',
            'res.resDate'=>'业务日期',
        ]);
    }

    /**
     * 数据上传
     * @param Request $request
     * @param $integrate
     * @param $version
     * @param $enumType
     * @return Factory|RedirectResponse|View
     */
    public function upload(Request $request,$integrate,$version,$enumType){
        $resVersion=VersionModel::find($version);
        $resIntegrate=IntegrateModel::find($integrate);
        if($resIntegrate){
            $chrIntegrateName=$resIntegrate->chrIntegrateName;
        }else{
            $chrIntegrateName='无';
        }

        if($request->isMethod('POST')){
            $file = $request->file('file');
            $res = $request->input('res');
            $fileName=$this->uploadFile($file);
            if($fileName){
                //调用解析方法
                $this->import('storage/app/public/'.$fileName,$version,$integrate,$res['resDate']);
                return redirect('camp/resource/upload/'.$integrate.'/'.$version.'/'.$enumType)
                    ->with('success','上传成功');
            }else{
                return redirect()->back()->with('error','上传失败');
            }
        }
        return view('campaign.case05.resource.upload',[
            'resDate'=>date('Y-m-d',time()),
            'version'=>$version,
            'chrVersionName'=>$resVersion->chrVersionName,
            'integrate'=>$integrate,
            'chrIntegrateName'=>$chrIntegrateName,
            'enumType'=>$enumType,
            'heading'=>'新增资源数据',
        ]);
    }

    /**
     * 处理上传文件
     * @param $file
     * @param string $disk
     * @return bool
     */
    function uploadFile($file, $disk='public'){
        // 1.是否上传成功
        if (! $file->isValid()) {
            return false;
        }
        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if(! in_array($fileExtension, ['xlsx', 'xls'])) {
            return false;
        }
        // 3.判断大小是否符合 2M
        $tmpFile = $file->getRealPath();
        if (filesize($tmpFile) >= 2048000) {
            return false;
        }
        // 4.是否是通过http请求表单提交的文件
        if (! is_uploaded_file($tmpFile)) {
            return false;
        }
        // 5.每天一个文件夹,分开存储, 生成一个随机文件名
        $fileName = date('Y_m_d').'/'.md5(time()) .mt_rand(0,9999).'.'. $fileExtension;
        if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile)) ){
            return $fileName;
        }
    }

    /**
     * 解析Excel文件内容
     * @param string $filePath
     * @param int $version
     * @param int $integrate
     * @param string $resDate
     */
    function import($filePath,$version,$integrate,$resDate){
        Excel::load($filePath, function($reader) use($version,$integrate,$resDate) {
            //处理公共参数
            $data['intVersionID']=$version;
            $data['intIntegrateID']=$integrate;
            $data['resDate']=$resDate;

            //0-all-整体数据
            $data['enumType']=0;
            $res=$this->readExcelSheet($reader,$data['enumType']);
            $obj=ResourceModel::firstOrNew($data);
            $jsonArray=array(
                'data'=>array(
                    $res[0][0]=>$res[2][0],
                    $res[0][1]=>$res[2][1],
                    $res[0][2]=>$res[2][2]
                )
            );
            $obj->textJson=json_encode($jsonArray);
            $obj->save();
            //api
            $data['enumType']=1;
            $res=$this->readExcelSheet($reader,$data['enumType']);
            $obj=ResourceModel::firstOrNew($data);
            $jsonArray=array(
                'data'=>array(
                    $res[0][0]=>$res[2][0],
                    $res[0][1]=>$res[2][1],
                    $res[0][2]=>$res[2][2],
                    $res[0][3]=>$res[2][3],
                    $res[0][4]=>$res[2][4],
                    $res[0][5]=>$res[2][5],
                    $res[0][6]=>$res[2][6],
                    $res[0][7]=>$res[2][7],
                    $res[0][8]=>$res[2][8],
                    $res[0][9]=>$res[2][9],
                    $res[0][10]=>$res[2][10],
                    $res[0][11]=>$res[2][11],
                    $res[0][12]=>$res[2][12],
                    $res[0][13]=>$res[2][13],
                    $res[0][14]=>$res[2][14],
                    $res[0][15]=>$res[2][15],
                    $res[0][16]=>$res[2][16],
                    $res[0][17]=>$res[2][17],
                    $res[0][18]=>$res[2][18],
                    $res[0][19]=>$res[2][19],
                )
            );
            $obj->textJson=json_encode($jsonArray);
            $obj->save();
            //Bug 缺陷数据
            $data['enumType']=2;
            $this->readExcelInDataBase($reader,$data);

            //3-listLeft
            $data['enumType']=3;
            $this->readExcelInDataBase($reader,$data);

            //4-listRight
            $data['enumType']=4;
            $this->readExcelInDataBase($reader,$data);

            //5-pmdLeft
            $data['enumType']=5;
            $this->readExcelInDataBase2($reader,$data);

            //6-pmdRight
            $data['enumType']=6;
            $this->readExcelInDataBase2($reader,$data);

            //7-story故事点进度
            $data['enumType']=7;
            $this->readExcelInDataBase($reader,$data);

            //water-水球数据
            $data['enumType']=8;
            $res=$this->readExcelSheet($reader,$data['enumType']);

            $obj=ResourceModel::firstOrNew($data);
            $jsonArray=array(
                'data'=>array(
                    $res[0][0]=>$res[2][0],
                    $res[0][1]=>$res[2][1],
                    $res[0][2]=>$res[2][2],
                    $res[0][3]=>$res[2][3]
                )
            );
            $obj->textJson=json_encode($jsonArray);
            $obj->save();
        });
    }

    /**
     * 读取Excel并入库
     * @param $reader
     * @param $data
     * @return
     */
    function readExcelInDataBase($reader,$data){
        $obj=ResourceModel::firstOrNew($data);
        $res=readExcelSheet($reader,$data['enumType']);
        $jsonArray=array();
        foreach ($res as $key=>$item){
            if ($key > 1){
                $jsonArray['data'][]=array(
                    $res[0][0]=>$item[0],
                    $res[0][1]=>$item[1],
                    $res[0][2]=>$item[2]
                );
            }
        }
        $obj->textJson=json_encode($jsonArray);
        $arg=$obj->save();
        return $arg;
    }

    /**
     * 读取Excel并入库
     * @param $reader
     * @param $data
     */
    function readExcelInDataBase2($reader,$data){
        $obj=ResourceModel::firstOrNew($data);
        $res=readExcelSheet($reader,$data['enumType']);
        $jsonArray=array();
        foreach ($res as $key=>$item){
            if ($key > 1){
                $jsonArray['data'][]=array(
                    $res[0][0]=>$item[0],
                    $res[0][1]=>$item[1],
                    $res[0][2]=>$item[2],
                    $res[0][3]=>$item[3]
                );
            }
        }
        $obj->textJson=json_encode($jsonArray);
        $obj->save();
    }

    /**
     * Excel解析，按sheet索引解析数据
     * @param $reader
     * @param $sheet
     * @return array
     */
    function readExcelSheet($reader,$sheet){
        $arg=array();
        //获取excel的第几张表
        $reader = $reader->getSheet($sheet);
        //获取表中的数据
        $data = $reader->toArray();
        foreach ($data as $item){
            if($item[0]){
                $arg[]=$item;
            }
        }
        return $arg;
    }


    /**
     * Excel解析按sheet标题解析数据
     * @param $reader
     * @param $title
     * @return array
     */
    function readExcelSheetTitle($reader,$title){
        $arg=array();
        //获取excel的第几张表
        $reader = $reader->getTitle($title);
        //获取表中的数据
        $data = $reader->toArray();
        foreach ($data as $item){
            if($item[0]){
                $arg[]=$item;
            }
        }
        return $arg;
    }

    /**
     * 模板下载
     * @return BinaryFileResponse
     */
    public function download()
    {
        $file=storage_path('download/YS质量分析上传数据模板.xlsx');
        return response()->download($file);
    }


}
