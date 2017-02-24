<?php
namespace Home\Controller;
use Think\Controller;
class OppotunityController extends Controller {
	
	public function add(){
		
	}
    public function showall(){
		$user = M("user");
		$filter['crm_user.uid'] = $_GET['uid'];
		$date = $_GET['date'];
		if($date == 1){//最新操作降序
			$result = $user->field('crm_oppotunity.name,crm_customer.oid,crm_customer.update_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_oppotunity.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 2){//最新操作升序
			$result = $user->field('crm_oppotunity.name,crm_customer.oid,crm_customer.update_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_oppotunity.cid')->order('date asc')->where($filter)->select();
		}
		else if($date == 3){//创建时间降序
			$result = $user->field('crm_oppotunity.name,crm_customer.oid,crm_customer.create_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_oppotunity.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 4){//创建时间升序
			$result = $user->field('crm_oppotunity.name,crm_customer.oid,crm_customer.create_date as date,crm_customer.cid')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_oppotunity.cid')->order('date asc')->where($filter)->select();
		}
		if(false !== $result){
			$status = 'T';
			$msg = '查询成功';
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