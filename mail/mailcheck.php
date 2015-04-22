<?php
	ignore_user_abort(); 	//即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
	set_time_limit(0); // 执行时间为无限制，php默认执行时间是30秒，可以让程序无限制的执行下去
	$interval=10; // 每隔10‘运行一次
	do{
		sleep($interval);
		$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
		mysql_select_db("app_wcjdemo",$link);    //选择数据库
		
		//查找窗户状态
		$result=mysql_query("SELECT * FROM status");
		while($result_array=mysql_fetch_array($result)){
			if($result_array['Id']==1){
				$Windowstatus=$result_array['Windowstatus'];
			}
		}
		if($Windowstatus==1)
		{
			//更新数据库
			$nowtime=date("Y/m/d H:i:s",time());
			$sql="UPDATE mailcheck SET Mailstatus=1,Time='$nowtime' WHERE Id=1";
			if(!mysql_query($sql,$link)){
				die('Error:'.mysql_error());
			}
			mysql_close($link);
		}
	}while(true)
?>