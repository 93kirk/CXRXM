<?php
namespace Home\Controller;
use Think\Controller;
class VisitController extends Controller {
    public function add(){
		$visit = M("visit");
		$test['name'] = $_GET['name'];
		$test['create_by'] = $_GET['uname'];
		$test['cid'] =  $_GET['cid'];
		$result = $visit->where($test)->select();
		
		if($result != null){
			$status = 'F';
		    $msg = '该拜访已经创建';
		}
		else{
			$data['cid'] = $_GET['cid'];
			$data['name'] = $_GET['name'];
		    $data['create_by'] = $_GET['uname'];
		    $data['create_date'] = time();
	    	$data['update_date'] = $data['create_date'];
			$data['update_by'] = $data['uname'];
	    	$data['deal_status'] = 0;
	    	if($_GET['money']!=null){
	    		$data['money'] = $_GET['money'];
	    	}
	    	if($_GET['trade']!=null){
	    		$data['trade_day'] = strtotime($_GET['trade']);
	    	}
	    	$result = $opportunity->add($data);
			
		    if(false !== $result&&$result != null){
			$status = 'T';
			$msg = '添加成功';
		    }
		    else if(false !== $result&&$result == null){
		    	$status = 'F';
		    	$msg = '添加信息为空';
		    }
		    else{
		    $status = 'F';
		    $msg = '添加失败';
		    }
		}
		$response = array(
		        'msg' => $msg,
				'status'=>$status,
				);
		echo json_encode($response);
	}
	}
}