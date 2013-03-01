<?php
class ContentAction extends CommonAction{
	/*
		添加节目
	*/
	public function add()
	{
		if(isset($_POST["contentname"]))
		{

			$data = array();
            $data["classid"]        = $_POST["classid"];
			$data["contentname"]	= $_POST["contentname"];
			$data["member"] 		= $_POST["member"];
			$data["intro"] 			= $_POST["intro"];
			$data["number"]	  		= $_POST["number"];

			$info = $this->upload(3);
#			exit;
            $data["mp3address"]	= "/Common/Uploads/MP3/".$info[0]["savename"];

			$Content = M('Content');
            $result = $Content->add($data);
            if ( $result ){
                //成功提示
                $this->success('增加节目成功',U('Content/manage'));
            }
            else{
                //错误提示
                $this->error('增加节目失败');
            }
	   	}
		else
		{
            $Class = M("Class");
            $Class = $Class->field('classid,classname')->select();
            $this->assign("ClassList",$Class);
			$this->display();
		}
	}
	public function manage()
	{

		if(isset($_POST['duoxuanHidden'])) {
			$id = $_POST['duoxuanHidden'];
			
			$model = M("Content");
			$map['contentid'] = array('in',$id);
			$mp3s = $model->where($map)->select();
			$mp3length = count($mp3s);
			for($i=0;$i<$mp3length;$i++)
			{
				$this->del_file($mp3s[$i]['mp3address']);
			}
			$result = $model->where($map)->delete();
		}
		$ContentList = array();
		
        //$Content = M("Content");
		$page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

		//$Content= $Content->order('contentid asc')->select();
        $Model = new Model();
        $Content = $Model->query("select contentid,contentname,classname,member,number,mp3address from feel_content,feel_class
where feel_content.classid = feel_class.classid");
		while (list($key, $val) = each($Content)) {
		    array_push($ContentList,$val);
		}
		
		import("ORG.Util.Page");// 导入分页类
		$count = count($ContentList);// 查询满足要求的总记录数
		$length = 10;
		$offset = $length * ($page - 1);
		$Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
		$Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
		$show = $Page->show();// 分页显示输出
		$this->assign("ContentList",$ContentList);
		$this->assign("offset",$offset);
		$this->assign("length",$length);
		$this->assign("page",$show);
		$this->display();

	}
	public function edit()
    {

        if(isset($_POST["contentid"]))
        {
            $data["classid"]        = $_POST["classid"];
            $data["contentname"] = $_POST["contentname"];
			$data["member"] = $_POST["member"];
			$data["intro"] = $_POST["intro"];
			$data["number"]	  = $_POST["number"];
            //$data["mp3address"]	  = $_POST["mp3address"];

            
            //$_FILES["mp3address"]['name'] = $data["mp3address"];
            if(!empty($_FILES['mp3address']['name']))
            {
            	$info = $this->upload(3);
            	$data["mp3address"]	= "/Common/Uploads/MP3/".$info[0]["savename"];
            }

            $Content = M('Content');
            if(isset($_POST['contentid']))
            {
                $condition['contentid'] = $_POST['contentid'];
	
				if(!empty($_FILES['mp3address']['name']))
            	{	
            		$oldmp3 = $Content->where($condition)->find();
            		if($data['mp3address'] != $oldmp3['mp3address'])
                	{
                    	$this->del_file($oldmp3['mp3address'],3);
                	}
                }	
                //编辑数据
                $result = $Content->where($condition)->save($data);
                if ($result)
                {
                    //成功提示
                    $this->success('编辑节目成功',U('Content/manage'));
                }
                else
                {
                    //错误提示
                    $this->error('编辑节目失败',U('Content/manage'));
                }
            } 
        }
        else
        {
            $contentid = $_GET['id'];           
            $Content = M("Content");
            $condition['contentid'] = $_GET['id'];
            $Content = $Content->where($condition)->find();
            $this->assign("Content",$Content);

            $Class = M("Class");
            $Class = $Class->field('classid,classname')->select();
            $this->assign("ClassList",$Class);
            $this->display();
        }
    }
    public function del()
    {
        $this->assign("jumpUrl",U('Content/manage'));
        $contentid = $_GET['id'];
        //echo $contentid;
        //exit;
        $condition['contentid'] = $contentid;
        $Content = M('Content');
        $oldmp3 = $Content->where($condition)->find();

        $this->del_file($oldmp3['mp3address'],3);
        
	    $result = $Content->where($condition)->delete();
        if ($result) {
            //成功提示
            $this->success('节目删除成功');
        } else {
            //错误提示
            $this->error('节目删除失败');
        }
    }

}
