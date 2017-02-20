<?php
namespace Home\Controller;
use Think\Controller;
class CustomerController extends Controller {
    public function add(){//添加新客户
		
		$customer = M("customer");
		$data['name'] = $_GET['name'];
		$data['create_by'] = $_GET['createby'];
		$data['create_date'] = time();
		$data['update_date'] = $data['create_date'];
		$data['is_abandon'] = 0;
		if($_GET['mobile']!=null){
			$data['mobile'] = $_GET['mobile'];
		}
		if($address!=null){
			$data['address'] = $_GET['address'];
		}
		$customer->add($data);
		
	}
	public function update(){//更新客户信息
		$customer = M("customer");
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
	}
}