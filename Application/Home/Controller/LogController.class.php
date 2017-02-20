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
				$user->add($data);
			}
			else{
				$msg='两次密码输入不同或密码不足6位';
			    $data['mobile'] = $mobile;
                $data['pwd1'] = $pwdinput1;
			    $data['pwd2'] = $pwdinput2;
			}
		}
		else{
			$msg='手机号码格式有误或已注册';
			$data['mobile'] = $mobile;
            $data['pwd1'] = $pwdinput1;
			$data['pwd2'] = $pwdinput2;
		}
		$response = array(
                'type'  => 'json', 
                'message' => $msg,
				//'testinput'=>$input,
				'data'=>$data,
				);
		echo json_encode($response);
	}
    public function login(){//登录
		$input=$GLOBALS['HTTP_RAW_POST_DATA'];
		$inputdone=json_decode($input,true);
		$uname=$inputdone['account'];
		$pwdinput=$inputdone['pwd'];
		if($uname==null||$pwdinput==null){//判断账号密码是否为空
			$msg = '账号或密码为空';
		}
        else{
			$pwd=md5($pwdinput);
			$user = M("user"); // 实例化user对象
            $data['mobile|login_name'] = $uname;
			$result=$user->field('password')->where($data)->select(); 
			if($pwd==$result[0]['password']){//判断密码
				$msg='登录成功';
			}
			else{
				$msg='账号或密码有误';
			}
		}
		$data=array(
		        'uname'=>$uname,
				'pwd'=>$pwdinput,
		        );
		$response = array(
                'type'  => 'json', 
                'message' => $msg,
				'data'=>$data,
				);
		
		echo json_encode($response);
	}
}