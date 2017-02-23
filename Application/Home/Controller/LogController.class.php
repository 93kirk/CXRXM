<?php
namespace Home\Controller;
use Think\Controller;
class LogController extends Controller {
	//注册
	public function signup(){
		$input=$GLOBALS['HTTP_RAW_POST_DATA'];
		$inputdone=json_decode($input,true);
		//var_dump($inputdone);
		//$ip = get_client_ip();
		$mobile=$inputdone['mobile'];
		$pwdinput1=$inputdone['pwd1'];
		$pwdinput2=$inputdone['pwd2'];
		$user = M("user"); // 实例化user对象
        $data['mobile'] = $mobile;
		$result=$user->where($data)->select();
		if(preg_match("/^1[34578]\d{9}$/", $mobile)&&$result==null){//判断手机号大致格式
			if($pwdinput1==$pwdinput2&&strlen($pwdinput1)>=6){//判断两次密码是否相同，以及密码长度
				$pwd=md5($pwdinput1);
				$msg='注册成功';
				$user = M("user"); // 实例化user对象
                $data['mobile'] = $mobile;
                $data['password'] = $pwd;
				$data['create_date'] = time();
				$result=$user->add($data);
			}
			else{
				$msg = '两次密码输入不同或密码不足6位';
			    $data['mobile'] = $mobile;
                $data['pwd1'] = $pwdinput1;
			    $data['pwd2'] = $pwdinput2;
			}
		}
		else{
			$msg = '手机号码格式有误或已注册';
			$data['mobile'] = $mobile;
            $data['pwd1'] = $pwdinput1;
			$data['pwd2'] = $pwdinput2;
		}
		if(false !== $result){
			$status = 'T';
		}
		else{
			$status = 'F';
		}
		$response = array(
                'message' => $msg,
				'data'=>$data,
				'status'  => $status, 
				);
		echo json_encode($response);
	}
    public function login(){//登录
		$input = $GLOBALS['HTTP_RAW_POST_DATA'];
		$inputdone = json_decode($input,true);
		$uname = $inputdone['uname'];
		$pwdinput = $inputdone['pwd'];
		if($uname == null||$pwdinput==null){//判断账号密码是否为空
			$msg = '账号或密码为空';
		}
        else{
			$pwd=md5($pwdinput);
			$user = M("user"); // 实例化user对象
            $data['mobile|login_name'] = $uname;
			$result = $user->field('password,uid')->where($data)->select(); 
			if($pwd == $result[0]['password']){//判断密码
				$msg = '登录成功';
				$data2['login_time'] = time();
				$user->where($data)->save($data2);
			}
			else{
				$msg = '账号或密码有误';
			}
		}
		if(false !== $result){
			$status = 'T';
		}
		else{
			$status = 'F';
			$msg = '数据库操作有误';
		}
		$data = array(
				'pwd'=>$pwdinput,
				'uid'=>$result[0]['uid'],
				'mobile'=>$result[0]['mobile'],
		        );
		$response = array(
		        'msg' => $msg,
				'content'=>$data,
				'status'=>$status,
				//'login_time'=>$data2,
				);
		
		echo json_encode($response);
	}
	public function update(){//编辑信息
		$input = $GLOBALS['HTTP_RAW_POST_DATA'];
		$inputdone = json_decode($input,true);
		$data['mobile'] = $inputdone['mobile'];
		$pwd1 = $inputdone['pwd1'];
		$pwd2 = $inputdone['pwd2'];
		
		$user = M("user"); 
	    $data1['password'] = md5($pwd1);
		$data1['update_time'] = time();
		//验证码空缺
		if($pwd1 == $pwd2){
			$result = $user->where($data)->save($data1);
			$msg = "密码修改成功";
		}
		else{
			$msg = "两次密码输入不同或密码不足6位";
		}
		if($result !== false){
			$status = 'T';
		}
		else{
			$status = 'F';
		}
		$response = array(
		        'msg' => $msg,
				'content'=>'',
				'status'=>$status,
				//'login_time'=>$data2,
				);
	}
}