<?php
namespace Home\Controller;
use Think\Controller;
class CustomerController extends Controller {
	
    public function add(){//添加新客户
		$customer = M("customer");
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
	
	public function update(){//更新客户信息
		$customer = M("customer");
		$user = $_GET['uid'];
		$filter['name'] = $_GET['name'];
		if($_GET['nameup']!=null){
			$data['name'] = $_GET['nameup'];
		}
		if($_GET['mobile']!=null){
			$data['mobile'] = $_GET['mobile'];
		}
		if($_GET['address']!=null){
			$data['address'] = $_GET['address'];
		}
		if($_GET['profession']!=null){
			$data['profession'] = $_GET['profession'];
		}
		if($_GET['remark']!=null){
			$data['remark'] = $_GET['remark'];
		}
		$result = $customer->where($filter)->save($data);
		if(false !== $result&&$result != null){
			    $status = 'T';
		    	$msg = '修改成功';
		    }
		else if(false !== $result&&$result == null){
		    	$status = 'F';
		    	$msg = '客户信息未修改';
		    }
		else{
		    $status = 'F';
		    $msg = '修改失败';
		}
		$response = array(
		        'msg' => $msg,
				'content'=>$data,
				'status'=>$status,
				);
		echo json_encode($response);
	}
	
	public function showall(){//客户列表
		$user = M("user");
		$filter['crm_user.uid'] = $_GET['uid'];
		$date = $_GET['date'];
		if($date == 1){//最新操作降序
			$result = $user->field('crm_customer.name,crm_customer.cid,crm_customer.update_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 2){//最新操作升序
			$result = $user->field('crm_customer.name,crm_customer.cid,crm_customer.update_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date asc')->where($filter)->select();
		}
		else if($date == 3){//创建时间降序
			$result = $user->field('crm_customer.name,crm_customer.cid,crm_customer.create_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 4){//创建时间升序
			$result = $user->field('crm_customer.name,crm_customer.cid,crm_customer.create_date as date,crm_customer.cid')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date asc')->where($filter)->select();
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
	
    public function detail(){//客户详情
		$customer = M("customer");
		$filter['crm_customer.cid'] = $_GET['cid'];
		$result = $customer->field('crm_customer.cid as cid,crm_customer.name as name,crm_customer.mobile as mobile,crm_customer.address as address,crm_customer.profession as profession,crm_customer.remark as remark,crm_customer.deal_status as status,crm_customer.update_date as update_date,crm_customer.create_by as create_by')
		    //->join('LEFT JOIN crm_opportunity on crm_customer.cid = crm_opportunity.cid')
		    //->join('LEFT JOIN crm_visit on crm_customer.cid = crm_visit.cid')
			//->join('LEFT JOIN crm_user_customer_act on crm_customer.cid = crm_user_customer_act.cid')
			->where($filter)->select();
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
	
	public function find(){//客户查询
		$customer = M("customer");
		$filter['crm_customer.name'] = array('like' , '%'.$_GET['name'].'%');
		$filter['uid'] = $_GET['uid'];
		$result = $customer->
		join('crm_rl_user_customer ON crm_customer.cid = crm_rl_user_customer.cid')->
		field('name')->where($filter)->select();
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