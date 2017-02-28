<?php
namespace Home\Controller;
use Think\Controller;
class ContacterController extends Controller {
    public function add(){
		$contacter = M("contacter");
		$rl = M("rl_user_customer");
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
			$data1['name'] = $data['name'];
			$result1 = $contacter->field('coid')->where($data1)->select();
			$data_rl['uid'] = $_GET['uid'];
			$data_rl['coid'] = $result1[0]['coid'];
			$rl->add($data_rl);
			
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
	
	public function find(){//联系人查询
		$contacter = M("contacter");
		$filter['crm_contacter.name'] = array('like' , '%'.$_GET['name'].'%');
		$filter['uid'] = $_GET['uid'];
		$result = $contacter->
		join('crm_rl_user_contacter ON crm_contacter.coid = crm_rl_user_contacter.coid')->
		field('name')->where($filter)->select();
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
		$date = $_GET['date'];
		if($date == 1){//最新操作降序
			$result = $user->field('crm_contacter.name,crm_contacter.coid,crm_contacter.update_date as date')
		    ->join('crm_rl_user_contacter ON crm_user.uid = crm_rl_user_contacter.uid')
		    ->join('crm_contacter ON crm_rl_user_contacter.coid = crm_contacter.coid')->order('date desc')->where($filter)->select();
		}
		else if($date == 2){//最新操作升序
			$result = $user->field('crm_contacter.name,crm_contacter.coid,crm_contacter.update_date as date')
		    ->join('crm_rl_user_contacter ON crm_user.uid = crm_rl_user_contacter.uid')
		    ->join('crm_contacter ON crm_rl_user_contacter.coid = crm_contacter.coid')->order('date asc')->where($filter)->select();
		}
		else if($date == 3){//创建时间降序
			$result = $user->field('crm_contacter.name,crm_contacter.coid,crm_contacter.create_date as date')
		    ->join('crm_rl_user_contacter ON crm_user.uid = crm_rl_user_contacter.uid')
		    ->join('crm_contacter ON crm_rl_user_contacter.coid = crm_contacter.coid')->order('date desc')->where($filter)->select();
		}
		else if($date == 4){//创建时间升序
			$result = $user->field('crm_contacter.name,crm_contacter.coid,crm_contacter.create_date as date,crm_contacter.coid')
		    ->join('crm_rl_user_contacter ON crm_user.uid = crm_rl_user_contacter.uid')
		    ->join('crm_contacter ON crm_rl_user_contacter.coid = crm_contacter.coid')->order('date asc')->where($filter)->select();
		}
		if(false !== $result&&$result != null){
			$status = 'T';
			$msg = '查询成功';
			$i = 0;
			while($result[$i]['date'] != null){
				$result[$i]['date'] = date("Y-m-d",$result[$i]['date']);
				$i++;
			}
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