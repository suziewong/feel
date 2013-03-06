<?php
class DJAction extends CommonAction {

    public function add()
    {
    	if(isset($_POST["username"]))
    	{
            $data = array();
    		if(empty($_POST['username']))
            {
                $this->error("用户名为空");
            }
            if(empty($_POST['nickname']))
            {
                $this->error("昵称为空");
            }
            if(empty($_POST['weibo-name']))
            {
                $this->error("微博名为空");
            }
            if(empty($_POST['weibo-url']))
            {
                $this->error("微博地址为空");
            }
    		$data["name"] 	  = $_POST["username"];
    		$data["nickname"] = $_POST["nickname"];
    		$data["starsign"] = $_POST["starsign"];
    		$data["hobby"]	  = $_POST["hobby"];
    		$data["intro"] = $_POST["intro"];
    		$data["weibo-name"] = $_POST["weibo-name"];
    		$data["weibo-url"] = $_POST["weibo-url"];
    		$data["total"] = $_POST["total"];
    		
            //开始处理Face图片
            if($_FILES["faceaddress"])
            {
                $info = $this->upload(1);
                $data["faceaddress"] = "/Common/Uploads/DJ/Face/".$info[0]["savename"];
            }
            if($_FILES["headaddress"])
            {
                $info = $this->upload(2);
                $data["headaddress"] = "/Common/Uploads/DJ/Head/".$info[0]["savename"];
            }

            $Dj = M('Dj');
            if(isset($_POST['Djid'])) {
                //编辑数据
                $condition['id'] = $_POST['Djid'];
                $result = $Dj->where($condition)->save($data);
                if ( $result ) {
                    //成功提示
                    $this->success('编辑主播成功');
                } else {
                    //错误提示
                    $this->error('编辑主播失败');
                }
            } else {
                // 添加数据
                

                $result = $Dj->add($data);
                 $conditionname['name'] = $data["name"];
                 $djinfo = $Dj->field('id')->where($conditionname)->select();
                 //直接创建用户
                $userdata=array();
                $userdata['username'] = $data["name"];
                $userdata['userpassword'] = md5("123456");
                $userdata['userpower'] = 1;
                $userdata['djid'] = $djinfo[0]['id'];
                $user = M('User');
                $userresult = $user->add($userdata);
                
                if ( $result && $userresult){ 
                    //成功提示
                    $this->success('增加主播成功',U('DJ/manage'));
                } else {
                    //错误提示
                    $this->error('增加主播失败');
                }
            }

       	}
    	else
    	{
    		$this->display();
    	}
    }

    public function manage()
    {
        if(isset($_POST['duoxuanHidden'])) {
            $id = $_POST['duoxuanHidden'];
            
            $model = M("Dj");
			$Life = M('Life');
            $map['id'] = array('in',$id);

            $djs = $model->where($map)->select();
            $djlength = count($djs);
            for($i=0;$i<$djlength;$i++)
            {
                $this->del_file($djs[$i]['faceaddress']);
                $this->del_file($djs[$i]['headaddress']);
        		$condition2['djid'] = $djs[$i]['id'];
				$life = $Life->where($condition2)->select();
        		$lifelength = count($life);
        		for($i=0;$i<$lifelength;$i++)
        		{
           			$this->del_file($life[$i]['lifeaddress']);
				}
        		$result = $Life->where($condition2)->delete();	
            }
            
            $result = $model->where($map)->delete();
        }
    	$DJList = array();
    	$DJ = M("Dj");
    	$page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

    	$DJ= $DJ->order('id asc')->select();
    	while (list($key, $val) = each($DJ)) {
    	    array_push($DJList,$val);
    	}
    	
    	import("ORG.Util.Page");// 导入分页类
    	$count = count($DJList);// 查询满足要求的总记录数
    	$length = 10;
    	$offset = $length * ($page - 1);
    	$Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
    	$Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
    	$show = $Page->show();// 分页显示输出
    	$this->assign("DJList",$DJList);
    	$this->assign("offset",$offset);
    	$this->assign("length",$length);
    	$this->assign("page",$show);
    	$this->display();
    }
    public function edit()
    {
        if(session('userpower') != 0)
        {
            $_GET['id']=session('djid');
        }
        else
        {

        }
        if(isset($_POST["DJid"]))
        {
            $data["name"] 	  = $_POST["username"];
    		$data["nickname"] = $_POST["nickname"];
    		$data["starsign"] = $_POST["starsign"];
    		$data["hobby"]	  = $_POST["hobby"];
    		//$data["faceaddress"]= $_POST["faceaddress"];
    		//$data["headaddress"]= $_POST["headaddress"];
    		$data["intro"] = $_POST["intro"];
    		$data["weibo-name"] = $_POST["weibo-name"];
    		$data["weibo-url"] = $_POST["weibo-url"];
    		$data["total"] = $_POST["total"];
    		//var_dump($data);
    		//exit;
            //开始处理Face图片
            if(!empty($_FILES["faceaddress"]['name']))
            {
                $info = $this->upload(1);
                $data["faceaddress"] = "/Common/Uploads/DJ/Face/".$info[0]["savename"];
            }
            if(!empty($_FILES["headaddress"]['name']))
            {

                $info = $this->upload(2);
                $data["headaddress"] = "/Common/Uploads/DJ/Head/".$info[0]["savename"];
            }
            $user = M('Dj');
            if(isset($_POST['DJid']))
            {
                //编辑数据
                $condition['id'] = $_POST['DJid'];
                $result = $user->where($condition)->find();
                if(!empty($data['faceaddress']))
                {
                    $this->del_file($result['faceaddress']);
                }
                if(!empty($data['headaddress']))
                {
                    $this->del_file($result['headaddress']);
                }

                $result = $user->where($condition)->save($data);
                if ($result) 
                {
                    //成功提示
                    $this->success('编辑主播成功',U('DJ/manage'));
                }
                else
                {
                    //错误提示
                    $this->error('木有发生更改,编辑主播失败',U('DJ/manage'));
                }
            } 
        }
        else
        {
            $userid = $_GET['id'];
            $DJ = M("Dj");
            $condition['id'] = $_GET['id'];
            $DJ = $DJ->where($condition)->find();
            $this->assign("DJ",$DJ);
            $this->display();
        }
    }
    public function del()
    {
        $this->assign("jumpUrl",U('DJ/manage'));
        $id = $_GET['id'];
        //echo $id;
        //exit;
        $condition['id'] = $id;
        $DJ = M('Dj');
        $dj = $DJ->where($condition)->find();
        $this->del_file($dj['faceaddress']);
        $this->del_file($dj['headaddress']);
		$Life = M('Life');
        $condition2['djid'] = $id;
		$life = $Life->where($condition2)->select();
        $lifelength = count($life);
        for($i=0;$i<$lifelength;$i++)
        {
           $this->del_file($life[$i]['lifeaddress']);
		}
        $result = $Life->where($condition2)->delete();	
        $result = $DJ->where($condition)->delete();
        if ($result) {
            //成功提示
            $this->success('主播删除成功');
        } else {
            //错误提示
            $this->error('主播删除失败');
        }
    }

    public function addimage()
    {
        if(isset($_POST["DJid"]))
        {
            if(!empty($_FILES['life']['name'][0]))
            {
                $info = $this->upload(4);
            }
            else
            {
                $this->error('木有上传图片');
            }
            $lifecount = count($info);
            $Life = M("Life");
            $data = array();
            $data['djid'] = $_POST["DJid"];
            for($i=0; $i<$lifecount ; $i++)
            {
                $data['lifeaddress'] = "/Common/Uploads/DJ/Life/".$info[$i]["savename"];;
                //var_dump($data);
                $result = $Life->add($data);
                if($result){
                    //成功提示
                } else {
                    //错误提示
                    $this->error('增加主播生活照失败');
                }
            }
           // $re = '../DJ/manageimage/id/'.$_POST["DJid"];
            $re = U('DJ/manageimage?id='.$_POST["DJid"]);
            $this->success('增加生活照成功',$re);
           
        }
        else
        {
            $userid = $_GET['id'];
            $DJ = M("Dj");
            $condition['id'] = $_GET['id'];
            $DJ = $DJ->where($condition)->find();
            $this->assign("DJ",$DJ);
            $this->display();
        }
    }

    public function manageimage()
    {
        if($_POST['id'])
        {
            $length =count($_POST['id']);
            for($i=0; $i<$length-1; $i++)
            {
                $id .= $_POST['id'][$i];
                $id .= ',';
            }
            $id .=$_POST['id'][$length-1];
            
            $model = M("Life");
            $map['id'] = array('in',$id);

            $djlifes = $model->where($map)->select();
            $djlifelength = count($djlifes);
            for($i=0;$i<$djlifelength;$i++)
            {
                $this->del_file($djlifes[$i]['lifeaddress']);
            }
            $result = $model->where($map)->delete();
            //$re = '../DJ/manageimage/id/'.$_POST["djid"];
            $re = U('DJ/manageimage?id='.$_POST["djid"]);
            $this->success('编辑生活照成功',$re);
        }
        else
        {

        if(isset($_GET['id']))
        {
            $userid = $_GET['id'];
        }
        else
        {
            $userid = $_POST['djid'];
        }
        $Life = M("Life");
        $condition['djid'] = $userid;
        $Life = $Life->where($condition)->select();
        $this->assign("LifeList",$Life);

        $this->display();
        }
    }
    public function view()
    {
        echo "等待前端";
    }
}
