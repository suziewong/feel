 <?php
 	class ClassAction extends CommonAction{

 		public function add()
 		{
			if(isset($_POST["classname"]))
			{
				$data = array();
				$data["classname"]	= $_POST["classname"];
				$data["intro"] 		= $_POST["intro"];      

				$Class = D('Class');
				if (!$Class->create()){ // 创建数据对象
				    // 如果创建失败 表示验证没有通过 输出错误提示信息
				    exit($Class->getError());
				}else{
				    // 验证通过 写入新增数据
				    //$Class->add();
				    $result = $Class->add($data);
				    if ( $result ){
				        //成功提示
				        $this->success('增加栏目成功',U('Class/manage'));
				    }
				    else{
				        //错误提示
				        $this->error('增加栏目失败');
				    }
				}
		   	}
			else
			{
				$this->display();
			}
 		}
 	public function edit()
 	{
        if(isset($_POST["classid"]))
        {
            $data["classname"] = $_POST["classname"];
			$data["intro"] = $_POST["intro"];
            $Class = M('Class');

            $condition['classid'] = $_POST['classid'];

            //编辑数据
            $result = $Class->where($condition)->save($data);
            if ($result)
            {
                //成功提示
                $this->success('编辑节目成功',U('Class/manage'));
            }
            else
            {
                //错误提示
                $this->error('编辑节目失败',U('Class/manage'));
            }
            
        }
        else
        {
 			$classid = $_GET['id'];           
            $Class = M("Class");
            $condition['classid'] = $_GET['id'];
            $Class = $Class->where($condition)->find();
            $this->assign("Class",$Class);
            $this->display();
        }
 	}
 	public function manage()
 	{
 		if(isset($_POST['duoxuanHidden'])) {
 			$id = $_POST['duoxuanHidden'];
 			
 			$model = M("Class");
 			$map['classid'] = array('in',$id);
 			$result = $model->where($map)->delete();
 		}

 		$ClassList = array();
 		$Class = M("Class");
 		$page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

 		$Class= $Class->order('classid asc')->select();
 		while (list($key, $val) = each($Class)) {
 		    array_push($ClassList,$val);
 		}
 		import("ORG.Util.Page");// 导入分页类
 		$count = count($ClassList);// 查询满足要求的总记录数
 		$length = 10;
 		$offset = $length * ($page - 1);
 		$Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
 		$Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
 		$show = $Page->show();// 分页显示输出
 		$this->assign("ClassList",$ClassList);
 		$this->assign("offset",$offset);
 		$this->assign("length",$length);
 		$this->assign("page",$show);
 		$this->display();		
 	}


 	public function del()
 	{
        $this->assign("jumpUrl",U('Class/manage'));
        $classid = $_GET['id'];

        $condition['classid'] = $classid;
        $Class = M('Class');     
	    $result = $Class->where($condition)->delete();
        if ($result) {
            //成功提示
            $this->success('栏目删除成功');
        } else {
            //错误提示
            $this->error('栏目删除失败');
        }
 	}

 }