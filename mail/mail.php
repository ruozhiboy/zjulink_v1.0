<?php
	ignore_user_abort(); 	//即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
	set_time_limit(0); // 执行时间为无限制，php默认执行时间是30秒，可以让程序无限制的执行下去
	$interval=10*60; // 每隔10‘运行一次
	do{
		sleep($interval); // 按设置的时间等待循环执行
		$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
		mysql_select_db("app_wcjdemo",$link);    //选择数据库
	
		//查找窗户状态
		$result=mysql_query("SELECT * FROM mailcheck");
		while($result_array=mysql_fetch_array($result)){
			if($result_array['Id']==1){
				$Mailstatus=$result_array['Mailstatus'];
			}
		}

		//如果窗户有人，则发送邮件
		if($Mailstatus==1)
		{
			$to = "wcj.zju@foxmail.com";
			$subject = "安全警告！";
			$message = "警告！窗户边有人经过，请注意。";
			$from = "linkatzju@arduino.com";
			$headers = "From: $from";
			mail($to,$subject,$message,$headers);
			echo "Mail Sent.";
		}
		mysql_close($link);
		sleep(120);
	}while(true);
?>