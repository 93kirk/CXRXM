<?php
namespace Home\Controller;
use Think\Controller;
class OpportunityController extends Controller {
	
	public function add(){//添加新商机
		$customer = M("opportunity");
		$rl = M("rl_user_customer");
		$test['name'] = $_GET['name'];
		$test['create_by'] = $_GET['uname'];
		
		$result = $customer->where($test)->select();
		
		if($result != null){
			$status = 'F';
		    $msg = '该客户名已被使用，请在后面添加区分标识';
		}
		else{
			$data['name'] = $_GET['name'];
		    $data['create_by'] = $_GET['uname'];
		    $data['create_date'] = time();
	    	$data['update_date'] = $data['create_date'];
	    	$data['is_abandon'] = 0;
	    	$data['is_fromclue'] = 0;
			$data['is_visit'] = 0;
	    	if($_GET['mobile']!=null){
	    		$data['mobile'] = $_GET['mobile'];
	    	}
	    	if($address!=null){
	    		$data['address'] = $_GET['address'];
	    	}
	    	$result = $customer->add($data);
			$data1['name'] = $data['name'];
			$result1 = $customer->field('cid')->where($data1)->select();
			$data_rl['uid'] = $_GET['uid'];
			$data_rl['cid'] = $result1[0]['cid'];
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
    public function showall(){
		$user = M("user");
		$filter['crm_user.uid'] = $_GET['uid'];
		$date = $_GET['date'];
		if($date == 1){//最新操作降序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.update_date as date,crm_opportunity.money as money')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_opportunity ON crm_rl_user_customer.cid = crm_opportunity.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 2){//最新操作升序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.update_date as date,crm_opportunity.money as money')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_opportunity ON crm_rl_user_customer.cid = crm_opportunity.cid')->order('date asc')->where($filter)->select();
		}
		else if($date == 3){//创建时间降序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.update_date as date,crm_opportunity.money as money')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_opportunity ON crm_rl_user_customer.cid = crm_opportunity.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 4){//创建时间升序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.update_date as date,crm_opportunity.money as money')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_opportunity ON crm_rl_user_customer.cid = crm_opportunity.cid')->order('date asc')->where($filter)->select();
		}
		if(false !== $result){
			$status = 'T';
			$msg = '查询成功';
			$i = 0;
			while($result[$i]['date'] != null){
				$result[$i]['date'] = date("Y-m-d",$result[$i]['date']);
				$i++;
			}
		}
		else if(false !== $result){
			$status = 'F';
			$msg = '暂无商机';
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