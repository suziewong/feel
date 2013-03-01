<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	
    public function index(){
    	//var_dump($config);
    	//C('HOME_DEFAULT_THEME');
    	$this->display(C('HOME_DEFAULT_THEME').':index');
	}
	public function dj()
	{
		$this->display(C('HOME_DEFAULT_THEME').':dj');
	}
	public function test()
	{
		//echo C('HOME_DEFAULT_THEME').'/feel-static:index';
		$this->display(C('HOME_DEFAULT_THEME').'/feel-static:index');
	}
}
