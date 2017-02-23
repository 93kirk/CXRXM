<?php
namespace Home\Controller;
use Think\Controller;
class CustomerController extends Controller {
	
    public function add(){//添加新客户

		$customer = M("customer");
		$test['name'] = $_GET['name'];
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
	    	if($_GET['mobile']!=null){
	    		$data['mobile'] = $_GET['mobile'];
	    	}
	    	if($address!=null){
	    		$data['address'] = $_GET['address'];
	    	}
	    	$result = $customer->add($data);
		    if(false !== $result){
		    	$status = 'T';
		    	$msg = '添加成功';
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
		$customer->where($filter)->save($data);
		
		$response = array(
		        'msg' => $msg,
				'content'=>'',
				'status'=>$status,
				);
		echo json_encode($response);
	}
	
	public function showall(){//客户列表
		$user = M("user");
		$filter['crm_user.uid'] = $_GET['uid'];
		$date = $_GET['date'];
		if($date == 1){
			$result = $user->field('crm_customer.name,crm_customer.update_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 2){
			$result = $user->field('crm_customer.name,crm_customer.update_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date asc')->where($filter)->select();
		}
		else if($date == 3){
			$result = $user->field('crm_customer.name,crm_customer.create_date as date')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date desc')->where($filter)->select();
		}
		else if($date == 4){
			$result = $user->field('crm_customer.name,crm_customer.create_date as date,crm_customer.cid')
		    ->join('crm_rl_user_customer ON crm_user.uid = crm_rl_user_customer.uid')
		    ->join('crm_customer ON crm_rl_user_customer.cid = crm_customer.cid')->order('date asc')->where($filter)->select();
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
	
    public function detail(){//客户详情
		$customer = M("customer");
		$filter['crm_customer.cid'] = $_GET['cid'];
		$result = $customer->join('crm_opportunity on crm_customer.cid = crm_opportunity.cid')
		    ->join('crm_visit on crm_customer.cid = crm_opportunity.cid')
			->join('crm_user_customer_act on crm_customer.cid = crm_user_customer_act.cid')
			->where($filter)->select();
		
    }
	
	public function find(){//客户查询
		$customer = M("customer");
		$filter['crm_customer.name'] = array('like' , '%'.$_GET['name'].'%');
		$result = $customer->field('name')->where($filter)->select();
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