<?php
	$Temperature=$_GET['Temperature'];  
    $Humidity=$_GET['Humidity'];
    $Windowstatus=$_GET['Windowstatus'];
    $Ledstatus=$_GET['Ledstatus'];  //获取GET中的参数
    $link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
    mysql_select_db("app_wcjdemo",$link);    //选择数据库
        
    //更新数据库
    $nowtime=date("Y/m/d H:i:s",time());
    $sql="UPDATE status SET Temperature='$Temperature',Humidity='$Humidity',Windowstatus='$Windowstatus',Ledstatus='$Ledstatus',Statustime='$nowtime' WHERE Id=1";
    if(!mysql_query($sql,$link)){
       die('Error:'.mysql_error());    
    }
        
    // 读出数据库中开关灯的指令
    $result=mysql_query("SELECT * FROM status");
    while($result_array=mysql_fetch_array($result)){
       if($result_array['Id']==1){
           $Led=$result_array['Led'];
       }
    }
        
    //关闭数据库
    mysql_close($link);
        
    //输出给arduino
    echo " {".$Led."}";
?>