<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<style>
*{
	padding:0px;
	margin:0px;
	}
body{
	font-family:Arial, Helvetica, sans-serif;
	background:url(images/grass.jpg);
	font-size:13px;
    background-size: 100%;
	}
img{
	border:0;
	}
.lg{width:468px; height:468px; margin:100px auto; background:url(images/login_bg.png) no-repeat;}
.lg_top{ height:200px; width:468px;}
.lg_main{width:400px; height:180px; margin:0 25px;}
.lg_m_1{
	width:290px;
	height:100px;
	padding:60px 55px 20px 55px;
}
.ur{
	height:37px;
	border:0;
	color:#666;
	width:236px;
	margin:4px 28px;
	background:url(images/user.png) no-repeat;
	padding-left:10px;
	font-size:16pt;
	font-family:Arial, Helvetica, sans-serif;
}
.pw{
	height:37px;
	border:0;
	color:#666;
	width:236px;
	margin:4px 28px;
	background:url(images/password.png) no-repeat;
	padding-left:10px;
	font-size:16pt;
	font-family:Arial, Helvetica, sans-serif;
}
.bn{width:330px; height:72px; background:url(images/enter.png) no-repeat; border:0; display:block; font-size:18px; color:#FFF; font-family:Arial, Helvetica, sans-serif; font-weight:bolder;}
.lg_foot{
	height:80px;
	width:330px;
	padding: 6px 68px 0 68px;
}
.ce{
	font-family:Arial, Helvetica, sans-serif; font-weight:bolder;font-size:18px;color:#A2CD5A;height:72px;text-align: center;
}
.be{
	font-family:Arial, Helvetica, sans-serif; font-weight:bolder;font-size:18px;color:#A2CD5A;height:50px;text-align: center;align:center;
}
</style>
</head>
<body class="b">
<div class="lg">
    <div class="lg_top"></div>
    <div class="lg_main">
        <div class="lg_m_1">
        <?php 
       		if($_SERVER['REQUEST_METHOD']=='POST')
       		{
       			$openid=$_POST['openid'];
       			$ctruser=$_POST['ctruser'];

	       			//连接数据库
	       			$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
	       			mysql_select_db("app_wcjdemo",$link);    //选择数据库
	       			
	       			//更新数据库
	       			$nowtime=date("Y/m/d H:i:s",time());
	       			//查询数据库
	       			$result=mysql_query("SELECT * FROM users");
	       			while($result_array=mysql_fetch_array($result)){
	       				if($result_array['Openid']==$openid){
	       					$Id=$result_array['Id'];
	       				}
	       			}

		       			if($ctruser=='add')
		       			{
		       				if(!$Id)
		       				{
			       				$sql="INSERT INTO users (Openid,Time) VALUES ('$openid','$nowtime')";
			       				if(!mysql_query($sql,$link)){
			       				die('Error:'.mysql_error());
			       				}
			       				echo "<br><br><br><p class=\"ce\">操作成功！</p>";
		       				}
		       				else
		       					echo "<br><br><br><p class=\"ce\">此用户已存在！</p>";
		       			}
		       			else if($ctruser=='delete')
		       			{
		       				if($Id)
		       				{
		       					$sql="DELETE FROM users WHERE Openid='$openid'";
		       					if(!mysql_query($sql,$link)){
		       						die('Error:'.mysql_error());
		       					}
		       					echo "<br><br><br><p class=\"ce\">操作成功！</p>";
		       			
		       				}
		       				else
		       					echo "<br><br><br><p class=\"ce\">没有此用户！</p>";
		       			}
		       			
    
       					else if($ctruser=="allusers")
       					{
       						$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
							mysql_select_db("app_wcjdemo",$link);
						
							$result=mysql_query("SELECT * FROM users");
							echo "<br><br><br><select class=\"be\">";
							while($row = mysql_fetch_array($result))
							{
						  		echo "<option>".$row['Openid']."</option>";
							}
							echo "</select>";
							mysql_close($con);
       					
       					}
       					
       					else
       						echo "<br><br><br><p class=\"ce\">请输入openid！</p>";
       		}
       	?>
        </div>
    </div>
    <div class="lg_foot">
    <button onclick="location.href='../login/index.php'" class="bn" >返回</button></div>
</div>
</body>
</html>