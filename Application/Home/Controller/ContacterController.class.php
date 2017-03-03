<?php
namespace Home\Controller;
use Think\Controller;
class ContacterController extends Controller {
    public function add(){
		$contacter = M("contacter");
		$rl = M("rl_user_contacter");
		$test['name'] = $_GET['name'];
		$test['create_by'] = $_GET['uid'];
		$result = $contacter->where($test)->select();
		if($result != null){
			$status = 'F';
		    $msg = '该联系人名称已被使用，请在后面添加区分标识';
		}
		else{
			$data['name'] = $_GET['name'];
		    $data['create_by'] = $_GET['uname'];
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
			$data1['name'] = $data['name'];
			$result1 = $contacter->field('coid')->where($data1)->select();
			$data_rl['uid'] = $_GET['uid'];
			$data_rl['coid'] = $result1[0]['coid'];
			$result2 = $rl->where($data_rl)->select();
			//var_dump($result2);
			if($result2 == null){
				$rl->add($data_rl);
			}
			
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
	
	public function update(){//更新联系人信息
		$contacter = M("contacter");
		$uname = $_GET['uname'];
		$filter['name'] = $_GET['name'];
		if($_GET['nameup']!=null){
			$data['name'] = $_GET['nameup'];
		}
		if($_GET['mobile']!=null){
			$data['mobile'] = $_GET['mobile'];
		}
		$data['update_date'] = time();
		$data['update_by'] = $uname;
		$result = $contacter->where($filter)->save($data);
		if(false !== $result&&$result != null){
			    $status = 'T';
		    	$msg = '修改成功';
		    }
		else if(false !== $result&&$result == null){
		    	$status = 'F';
		    	$msg = '联系人信息未修改';
		    }
		else{
		    $status = 'F';
		    $msg = '修改失败';
		}
		$response = array(
		        'msg' => $msg,
				//'content'=>$data,
				'status'=>$status,
				);
		echo json_encode($response);
	}
	
	public function detail(){//联系人详情
		$contacter = M("contacter");
		$filter['crm_contacter.coid'] = $_GET['coid'];
		$result = $contacter->field('crm_contacter.coid as coid,crm_contacter.name as name,crm_contacter.mobile as mobile')->
		where($filter)->select();
		$result1 = $result[0];
			//var_dump($result);
		if(false !== $result1){
			$status = 'T';
			$msg = '查询成功';
			$result1['update_date'] = date("Y-m-d",$result1['update_date']);
		}
		else{
			$status = 'F';
			$msg = '查询失败';
		}
		$response = array(
		        'msg' => $msg,
				'content'=>$result1,
				'status'=>$status,
				);
		echo json_encode($response);
    }
	
	public function find(){//联系人查询
		$contacter = M("contacter");
		$filter['crm_contacter.name'] = array('like' , '%'.$_GET['name'].'%');
		$filter['uid'] = $_GET['uid'];
		$result = $contacter->field('name,mobile,coid')->where($filter)->select();
		if(false !== $result&&$result != null){
			$status = 'T';
			$msg = '查询成功';
		}
		else if(false !== $result&&$result == null){
			$status = 'F';
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
	
	public function showall(){//联系人列表
		$user = M("user");
		$filter['crm_user.uid'] = $_GET['uid'];
		
		$result = $user->field('crm_contacter.name,crm_contacter.mobile,crm_contacter.coid')
		    ->join('crm_rl_user_contacter ON crm_user.uid = crm_rl_user_contacter.uid')
		    ->join('crm_contacter ON crm_rl_user_contacter.coid = crm_contacter.coid')->where($filter)->select();
		
		if(false !== $result&&$result != null){
			$status = 'T';
			$msg = '查询成功';
		}
		else if(false !== $result&&$result == null){
			$status = 'F';
			$msg = '暂无客户请添加或转化';
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
}