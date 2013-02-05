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
		$Dj = $Dj->select();
		echo json_encode($Dj);
	}
	//节目信息
	public function content()
	{
		$Content = M("Content");
		$Content = $Content->select();
		echo json_encode($Content);
		
	}	
}
