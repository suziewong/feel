<?php
class CommonAction extends Action {
    protected function _initialize() {
		if(session('userid')){
			
		}else{
			redirect(U('User/login'));
		}
	}
	
    public function index(){
    	
    }
	//文件上传
	Public function upload($tag){
		import('ORG.Net.UploadFile');
		//import('ORG.Util.Image');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize = C('UPLOAD_FILE_SIZE') ;// 设置附件上传大小
		$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg','zip','mp3','wma');// 设置附件上传类型
		$upload->saveRule = "uniqid";//生成随机的文件名
		if($tag==1)
		{
			$upload->savePath = C('FACE_UPLOAD_PATH');// 设置Face 头像上传目录
			//设置需要生成缩略图，仅对图像文件有效
			//echo __URL__;
			//exit;
			$upload->thumb = true;
			//设置需要生成缩略图的文件后缀
			$upload->thumbPrefix = '';  //生产2张缩略图
			//设置缩略图最大宽度
			$upload->thumbMaxWidth = "60";
			//设置缩略图最大高度
			$upload->thumbMaxHeight = "60";
			//设置生成缩略图后移除原图
			//$upload->thumbRemoveOrigin = true;
			$info =  $upload->uploadOne($_FILES['faceaddress']);
		}
		elseif($tag ==2)
		{
			$upload->savePath = C('HEAD_UPLOAD_PATH');// 设置Head头像上传目录
			//设置需要生成缩略图，仅对图像文件有效
			$upload->thumb = true;
			//设置需要生成缩略图的文件后缀
			$upload->thumbPrefix = '';  //生产2张缩略图
			//设置缩略图最大宽度
			$upload->thumbMaxWidth = "400";
			//设置缩略图最大高度
			$upload->thumbMaxHeight = "400";
			$info =  $upload->uploadOne($_FILES['headaddress']);
			//var_dump($info);
		}
        elseif($tag ==3)
        {
        	$upload->uploadReplace = true;
            $upload->savePath = C('MP3_UPLOAD_PATH');// 设置MP3上传目录
            $info = $upload->upload();
            $info = $upload->getUploadFileInfo();
        }
        else
        {
        	$upload->uploadReplace = true;
        	$upload->savePath = C('LIFE_UPLOAD_PATH');// 设置生活照上传目录
        	//设置需要生成缩略图，仅对图像文件有效
        	$upload->thumb = true;
        	//设置需要生成缩略图的文件后缀
        	$upload->thumbPrefix = '';  //生产2张缩略图
        	//设置缩略图最大宽度
        	//$upload->thumbMaxWidth = "400";
        	//设置缩略图最大高度
        	$upload->thumbMaxHeight = "400";
        	$info = $upload->upload();  	
            $info = $upload->getUploadFileInfo();
            //var_dump($info);
        	//exit;
        }
		if(!$info) {// 上传错误 提示错诣信息
#			var_dump($upload->getErrorMsg());
#exit;
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
#			var_dump($upload->getErrorMsg());
			
#		var_dump($info);
#exit;
		}
		return $info;
	}
	
	//删除旧文件
	public function del_file($filename)
	{
		 ///删除文件使用绝对路径

		$filename = $_SERVER['DOCUMENT_ROOT'].__ROOT__.$filename;
//		echo $filename;
//		exit;
		if(is_file( $filename ))
		{
		 	if( unlink($filename) )
		 	{
		 	 	//echo '文件删除成功';
		 	}
		 	else
		 	{
		  		//echo '文件删除失败，权限不够';
		 	}
		}
		else
		{
            	//	echo '不是有一个有效的文件';
		}
	}
}
