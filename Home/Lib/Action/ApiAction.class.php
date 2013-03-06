<?php
class ApiAction extends Action {
    public function _initialize()
    {
      
      header("Access-Control-Allow-Origin:*");
    }
    public function index(){
    	redirect(U('Index/index'));
	}
	//返回网站基础信息
	public function basic()
	{	
		$model = M("Setting");
		$setting = $model->getField('item_key,item_value');
		//$setting = $model->select();
	//	var_dump($setting);
		echo json_encode($setting);
	}

	//返回主播信息
	public function dj()
	{
		$Dj = M("Dj");
		$condition['id']=!empty($_GET['djid'])?$_GET['djid']:"";
		//var_dump($condition);
		if($condition['id'] == "")
		{
			$Dj = $Dj->select();
			for($i=0;$i<count($Dj);$i++)
			{
				
				$cond['djid'] = $Dj[$i]['id'];
				$Life = M("Life");
				$Life = $Life->where($cond)->select();
				for($j=0; $j<count($Life);$j++)
				{
					$Dj[$i]['lifePics'][$j] = $Life[$j]['lifeaddress'];
				}
				$Dj[$i]['headPic']= $Dj[$i]['headaddress'];
				$Dj[$i]['facePic']= $Dj[$i]['faceaddress'];
				$Dj[$i]['column']= $Dj[$i]['total'];
				$Dj[$i]['weibo']= $Dj[$i]['weibo-name'];
				$Dj[$i]['weiboUrl']= $Dj[$i]['weibo-url'];
				unset($Dj[$i]['headaddress']);
				unset($Dj[$i]['faceaddress']);
				unset($Dj[$i]['total']);
				unset($Dj[$i]['weibo-name']);
				unset($Dj[$i]['weibo-url']);
			}
		}
		else
		{
			$Dj = $Dj->where($condition)->select();
			$cond['djid'] = $condition['id'];
			$Life = M("Life");
			$Life = $Life->where($cond)->select();
			for($i=0; $i<count($Life);$i++)
			{
				$Dj[0]['lifePics'][$i] = $Life[$i]['lifeaddress'];
			}
			$Dj[0]['headPic']= $Dj[0]['headaddress'];
			$Dj[0]['facePic']= $Dj[0]['faceaddress'];
			$Dj[0]['column']= $Dj[0]['total'];
			$Dj[0]['weibo']= $Dj[0]['weibo-name'];
			$Dj[0]['weiboUrl']= $Dj[0]['weibo-url'];
			unset($Dj[0]['headaddress']);
			unset($Dj[0]['faceaddress']);
			unset($Dj[0]['total']);
			unset($Dj[0]['weibo-name']);
			unset($Dj[0]['weibo-url']);
		}
		echo json_encode($Dj);

	}
	//节目信息
	public function content()
	{
		$Content = M("Content");
		$contentid = isset($_GET['contentid'])?$_GET['contentid']:"";
		$condition['contentid']=$contentid;
		if($condition['contentid'] == "")
		{
			$classid = isset($_GET['classid'])?$_GET['classid']:2;
			$cond['classid']=$classid;
			$Content = $Content->where($cond)->select();
			for($i=0; $i<count($Content);$i++)
			{
						
				$Content[$i]['title'] = $Content[$i]['contentname'];
				$Content[$i]['file'] = $Content[$i]['mp3address'];
				$member = $Content[$i]['member'];
				unset($Content[$i]['contentname']);
				unset($Content[$i]['mp3address']);
				unset($Content[$i]['number']);
				unset($Content[$i]['member']);
				$Content[$i]['member'][0] = $member;	
			}

			
			
		}
		else
		{
			$Content = $Content->where($condition)->select();
			$Content[0]['title'] = $Content[0]['contentname'];
			$Content[0]['file'] = $Content[0]['mp3address'];
			$member = $Content[0]['member'];
			unset($Content[0]['contentname']);
			unset($Content[0]['mp3address']);
			unset($Content[0]['number']);
			unset($Content[0]['member']);
			$Content[0]['member'][0] = $member;	
		}

		
		echo json_encode($Content);
	}
	//主播生活照
	public function get_life()
	{
		$Life = M("Life");
		$djid = isset($_GET['djid'])?$_GET['djid']:"";
		$condition['djid']=$djid;

		if($condition['djid'] == "")
		{
			$Life = $Life->select();
		}
		else
		{
			$Life = $Life->where($condition)->select();
		}
		echo json_encode($Life);
	}
	/*
		评论
	*/
	public function comment()
	{
		$Comment = M("Comment");
		if(isset($_POST['djid']))
		{
		    $data = array();
		    $data['djid'] = $_POST['djid'];
		    $data['comment'] = $_POST['comment'];
		    $data['posttime']=time();
		    $Comment = M('Comment');
		    $result = $Comment->add($data);
		
		    if($result)
		    {
		        echo "{'result':'success'}";
		    }
		    else
		    {
		        echo "{'result':'fail'}"; 
		    }
		}
		else
		{
			 echo "{'result':'fail'}"; 
		}
	}
	/*
		建议
	*/
	public function suggest()
	{
		
		if(isset($_POST['suggest']))
        {

            $data = array();
            $data['name'] = $_POST['name'];
            $data['email'] = $_POST['email'];
            $data['suggest'] = $_POST['suggest'];
            $data['posttime']=time();
           
            $Suggest = M('Suggest');
            $result = $Suggest->add($data);
        	
            if($result)
            {
                echo "{'result':'success'}";
            }
            else
            {
                echo "{'result':'fail'}"; 
            }
        }
        else
        {
        	  echo "{'result':'fail'}"; 
        }
	}


}
