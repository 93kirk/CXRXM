<?php
namespace Home\Controller;
use Think\Controller;
class OpportunityController extends Controller {
	
	public function add(){//添加新商机
		$opportunity = M("opportunity");
		$rl = M("rl_user_customer");
		$test['name'] = $_GET['name'];
		$test['create_by'] = $_GET['uname'];
		
		$result = $opportunity->where($test)->select();
		
		if($result != null){
			$status = 'F';
		    $msg = '该商机名已被使用，请在后面添加区分标识';
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
	
	public function update(){//更新商机信息
		$opportunity = M("opportunity");
		$filter['oid'] = $_GET['oid'];
		$data['update_date'] = time();
		$data['update_by'] = $_GET['uname'];
		if($_GET['nameup']!=null){
			$data['name'] = $_GET['nameup'];
		}
		if($_GET['cid']!=null){
			$data['cid'] = $_GET['cid'];
		}
		if($_GET['money']!=null){
			$data['money'] = $_GET['money'];
		}
		if($_GET['trade']!=null){
			$data['trade_day'] = strtotime($_GET['trade']);
		}
		$result = $opportunity->where($filter)->save($data);
		if(false !== $result&&$result != null){
			    $status = 'T';
		    	$msg = '修改成功';
		    }
		else if(false !== $result&&$result == null){
		    	$status = 'F';
		    	$msg = '商机信息未修改';
		    }
		else{
		    $status = 'F';
		    $msg = '修改失败';
		}
		$data['update_date'] = date('Y-m-d',$data['update_date']);
		$data['trade_day'] = date('Y-m-d',$data['trade_day']);
		$response = array(
		        'msg' => $msg,
				//'content'=>$data,
				'status'=>$status,
				);
		echo json_encode($response);
	}
	
    public function showall(){
		$user = M("user");
		$filter['crm_user.uid'] = $_GET['uid'];
		$date = $_GET['date'];
		if($date == 1){//最新操作降序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.trade_day as date,crm_opportunity.money as money')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_opportunity ON crm_rl_user_customer.cid = crm_opportunity.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 2){//最新操作升序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.trade_day as date,crm_opportunity.money as money')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_opportunity ON crm_rl_user_customer.cid = crm_opportunity.cid')->order('date asc')->where($filter)->select();
		}
		else if($date == 3){//创建时间降序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.trade_day as date,crm_opportunity.money as money')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_opportunity ON crm_rl_user_customer.cid = crm_opportunity.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 4){//创建时间升序
			$result = $user->field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.trade_day as date,crm_opportunity.money as money')
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
	
	public function detail(){//商机详情
		$opportunity = M("opportunity");
		$filter['crm_opportunity.oid'] = $_GET['oid'];
		$result = $opportunity->field('crm_opportunity.name,crm_opportunity.cid as cid,crm_customer.name as cname,crm_opportunity.money,crm_opportunity.trade_day')
		    ->join('crm_customer on crm_customer.cid = crm_opportunity.cid')
		    //->join('LEFT JOIN crm_visit on crm_customer.cid = crm_visit.cid')
			//->join('LEFT JOIN crm_user_customer_act on crm_customer.cid = crm_user_customer_act.cid')
			->where($filter)->select();
		$result1 = $result[0];
			//var_dump($result);
		if(false !== $result1){
			$status = 'T';
			$msg = '查询成功';
			$result1['trade_day'] = date("Y-m-d",$result1['trade_day']);
			$result1['oid'] = $_GET['oid'];
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
	
	public function find(){//商机查询
		$opportunity = M("opportunity");
		$filter['crm_opportunity.name'] = array('like' , '%'.$_GET['name'].'%');
		$filter['uid'] = $_GET['uid'];
		$result = $opportunity->
		field('crm_opportunity.name as name,crm_opportunity.oid as oid,crm_opportunity.trade_day as date,crm_opportunity.money as money')->
		where($filter)->select();
		if(false !== $result&&$result != null){
			$status = 'T';
			$msg = '查询成功';
		}
		else if(false !== $result&&$result == null){
			$status = 'F';
			$msg = '您没有该客户信息';
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