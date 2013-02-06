<?php
class ApiAction extends Action {
    
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
		}
		else
		{
			$Dj = $Dj->where($condition)->select();
		}
		echo json_encode($Dj);

	}
	//节目信息
	public function content()
	{
		$Content = M("Content");
		$classid = isset($_GET['classid'])?$_GET['classid']:2;
		$condition['classid']=$classid;
		$Content = $Content->where($condition)->select();
		echo json_encode($Content);
	}	
}
