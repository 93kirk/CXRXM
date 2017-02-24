<?php
namespace Home\Controller;
use Think\Controller;
class ContacterController extends Controller {
    public function add(){
		$contacter = M("contacter");
		$test['name'] = $_GET['name'];
		$test['create_by'] = $_GET['uid'];
		$result = $contacter->where($test)->select();
		if($result != null){
			$status = 'F';
		    $msg = '该联系人名称已被使用，请在后面添加区分标识';
		}
		else{
			$data['name'] = $_GET['name'];
		    $data['create_by'] = $_GET['uid'];
		    $data['create_date'] = time();
	    	$data['update_date'] = $data['create_date'];
	    	$data['is_abandon'] = 0;
	    	$data['is_fromclue'] = 0;
	    	if($_GET['mobile']!=null){
	    		$data['mobile'] = $_GET['mobile'];
	    	}
	    	if($address!=null){
	    		$data['address'] = $_GET['address'];
	    	}
	    	$result = $contacter->add($data);
		    if(false !== $result&&$result != null){
			$status = 'T';
			$msg = '添加成功';
		    }
		    else if(false !== $result&&$result == null){
		    	$status = 'T';
		    	$msg = '添加信息为空';
		    }
		    else{
		    	$status = 'F';
		    	$msg = '添加失败';
		}
		}

		$response = array(
		        'msg' => $msg,
				'content'=>'',
				'status'=>$status,
				);
		echo json_encode($response);
    }
	
	public function find(){
		$contacter = M("contacter");
		$filter['crm_contacter.name'] = array('like' , '%'.$_GET['name'].'%');
		$result = $contacter->field('name')->where($filter)->select();
		if(false !== $result&&$result != null){
			$status = 'T';
			$msg = '查询成功';
		}
		else if(false !== $result&&$result == null){
			$status = 'T';
			$msg = '查无此人';
		}
		else{
			$status = 'F';
			$msg = '查询失败';
		}
		$response = array(
		        'msg' => $msg,
				'content'=>$result,
				'status'=>$status,
				);
		echo json_encode($response);
	}
	
	public function showall(){
		
	}
}