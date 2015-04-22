<?php 
	session_start();
?>
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
.lg{width:468px; height:468px; margin:100px auto;background:url(images/login_bg.png) no-repeat;}
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

.bn{width:330px; height:72px; background:url(images/enter.png) no-repeat; border:0; display:block; font-size:18px; color:#FFF; font-family:Arial, Helvetica, sans-serif; font-weight:bolder;}
.lg_foot{
	height:80px;
	width:330px;
	padding: 6px 68px 0 68px;
}
.ce{
	font-family:Arial, Helvetica, sans-serif; font-weight:bolder;font-size:12px;color:#A2CD5A;height:72px;text-align: center;
}
</style>
</head>
<body class="b">
<div class="lg">
<form action="index_ctr.php" method="post">
    <div class="lg_top"></div>
    <div class="lg_main">
        <div class="lg_m_1">
       	<?php 
       		if(!isset($_SESSION['username']))
       		{
       			echo "<br><br><br><p class=\"ce\">尚未登陆！</p>";
       			$page='login.html';
       			$url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
       			$url=rtrim($url,'/\\');
       			$url.='/'.$page;
       			echo "<script language=\"javascript\">";
       			echo "document.location=\"".$url."\"";
       			echo "</script>";	
       		}
       		else
       		{
       			echo "<input name=\"openid\" value=\"openid\" class=\"ur\" />";
       			echo "<p class=\"ce\"><input type=\"radio\" name=\"ctruser\" value=\"add\" class=\"ce\"/>ADD USER&nbsp;<input type=\"radio\" name=\"ctruser\" value=\"delete\" class=\"ce\"/>DELETE USER<input type=\"radio\" name=\"ctruser\" value=\"allusers\" class=\"ce\" checked=\"checked\" />ALL USERS</p>";
       		}
       	?>
        </div>
    </div>
    <div class="lg_foot">
    <input type="submit" value="submit" class="bn" /></div>
</form>
</div>
</body>
</html>