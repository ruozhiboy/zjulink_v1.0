<?php
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
    	$username=$_POST['username'];
    	$password=$_POST['password'];
    	
    	$checklogin=new login();
    	$page='index.php';
    	$flag=$checklogin->check($username,$password);
    	if($flag==1)
    	{
    		session_start();
    		$_SESSION['username']=$username;
    		$_SESSION['password']=$password;
    		$checklogin->rediret($page);
    	}
    	else
    	{
    		$string="<br><br><br><br><br><h2 style='font-family:Arial' align='center'>".implode(glue,$flag)."</h2>";
    		echo $string;
    	}
    }
    
	class login
	{
		public function rediret($page)
		{
			$url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
			$url=rtrim($url,'/\\');
			
			//add page
			$url.='/'.$page;
			
			header("Location:$url");
			exit();
		}	
		
		public function check($username,$password)
		{
			$error=array();
			
			if(empty($username))
			{
				$error[]="Forget Your Name!";
				return $error;
			}	
			
			else if(empty($password))
			{
				$error[]="Forget Your Password!";
				return $error;
			}
			else
			{
				if(($username=='admin')&&($password=='wang4089929945'))
					return 1;
				else
				{
					$error[]="Wrong Name or Password!";
					return $error;
				}
			}
		} 
	} 
?>