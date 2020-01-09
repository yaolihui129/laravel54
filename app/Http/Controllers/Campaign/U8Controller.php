<?php namespace App\Http\Controllers\Campaign;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class U8Controller extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		//
        $user=Auth::user();
        $pages=array();
        if(!empty($user)){
            $pages["login"]="1";
        }
        //return view("campaign.u8")->with($pages);

        $userU8 = $request->user();
        $userID = $userU8->id;
        //权限校验,按userid校验
        if ($userID == '24'||$userID == '23'||$userID == '27'||$userID == '39'||$userID == '40') {
            return view("campaign.u8")->with($pages);
        }else {
            echo '<script> 
                    var res=confirm("抱歉,当前账号不具备查看权限,如需下载请联系管理员");
                    if(res||!res){
                        javascript:window.opener=null;window.open(\'\',\'_self\');window.close();
                    }
                   </script>';
        }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	public function ult()
    {
        $file=storage_path('download/ULT.zip');
        return response()->download($file);
    }

    public function mtt()
    {
        $file=storage_path('download/MTT.zip');
        return response()->download($file);
    }
    public function sett()
    {
        $file=storage_path('download/SETT.zip');
        return response()->download($file);
    }
    public function dult()
    {
        $file=storage_path('download/DULT.zip');
        return response()->download($file);
    }
    public function pct()
    {
        $file=storage_path('download/PCT.zip');
        return response()->download($file);
    }
    public function js()
    {
        $file=storage_path('download/JS.zip');
        return response()->download($file);
    }
    public function lsbcx()
    {
        $file=storage_path('download/LSBCX.zip');
        return response()->download($file);
    }
    public function gdi()
    {
        $file=storage_path('download/GDI.zip');
        return response()->download($file);
    }
    public function sjkjgdb()
    {
        $file=storage_path('download/SJKJGDB.zip');
        return response()->download($file);
    }
    public function wj()
    {
        $file=storage_path('download/WJ.zip');
        return response()->download($file);
    }
    public function xn()
    {
        $file=storage_path('download/XN.zip');
        return response()->download($file);
    }
    public function ylzx()
    {
        $file=storage_path('download/YLZX.zip');
        return response()->download($file);
    }

}
