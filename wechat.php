<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechat();
//$wechatObj->valid();		//只用来和微信验证token，之后需要注释掉
//$wechatObj->vaildmail();	//只需要一次激活
$wechatObj->responseMsg();

class wechat
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	public function responseMsg()
	{
		//取得_POST数据
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
	
		//分析得到的数据
		if (!empty($postStr)){
			/* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
			 the best way is to check the validity of xml by yourself */
			libxml_disable_entity_loader(true);
	
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //利用php5.3自带函数，解析xml数据包
			$fromUsername = $postObj->FromUserName; //取得发送者的名字
			$toUsername = $postObj->ToUserName; //取得收信者名字
			$msgType=$postObj->MsgType; //取得数据类型
	
			$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";  //定义文本信息的xml格式

			$flag=$this->testname($fromUsername);
			//判断微信的openid是否被认证
			//如果被认证
			if($flag)
			{
				//如果输入的是文本
				if($msgType=="text"){
					$keyword = trim($postObj->Content);
					$time = time();
					$this->textandvoice($textTpl,$fromUsername,$toUsername,$keyword,$time);
				}
				//如果输入的是语音
				else if($msgType=="voice"){
					$keyword = trim($postObj->Recognition);
					$time = time();
					$this->textandvoice($textTpl,$fromUsername,$toUsername,$keyword,$time);
				}
				//如果是关注和取消关注事件
				else if($msgType=="event"){
					$msgTypeEvent=$postObj->Event;
					if($msgTypeEvent=='subscribe'){
						$time = time();
						$msgType = "text";
						$contentStr = '欢迎来到王传军写字的地方，不保证不会五毛，不保证政治正确，不保证不会乱扯，不定时更新，对我不要有期待哟！';
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					else{
						$time = time();
						$msgType = "text";
						$contentStr = '人生就是人与人谈恋爱，总是遇见与分开，遇见之时切莫热切，分开之时也勿拉扯~感谢对我的支持，再见，有缘再见！';
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					exit;
				}
				//不支持其他类型的输入
				else{
					$time = time();
					$msgType = "text";
					$contentStr = '目前不支持除文本以外的其他输入类型！';
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
					exit;
				}
			}
			//如果没有经过认证
			else
			{
				$time = time();
				$msgType = "text";
				$contentStr = "大王，你尚未授权访问此项目！你可以发送你的open_id(".$fromUsername.")到邮箱wcj.zju@hotmail.com申请访问权限。";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}
		}
		else {
			echo "";
			exit;
		}
	}
	
	private function textandvoice($textTpl,$fromUsername,$toUsername,$keyword,$time)
	{
		$msgType='text';
		/* 管理 */
		if(strstr($keyword, '管理')){
			$newsTpl="<xml>
					 <ToUserName><![CDATA[%s]]></ToUserName>
					 <FromUserName><![CDATA[%s]]></FromUserName>
					 <CreateTime>%s</CreateTime>
					 <MsgType><![CDATA[news]]></MsgType>
					 <ArticleCount>1</ArticleCount>
					 <Articles>
					 <item>
					 <Title><![CDATA[%s]]></Title> 
					 <Description><![CDATA[%s]]></Description>
					 <PicUrl><![CDATA[%s]]></PicUrl>
					 <Url><![CDATA[%s]]></Url>
					 </item>
					 </Articles>
					 </xml> ";
			$title='linkatzju后台管理系统';
			$description='';
			$picurl='http://120.26.103.180/login/images/wechat.png';
			$url='http://120.26.103.180/login/login.html';
			$resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $title, $description,$picurl,$url);
			echo $resultStr;
		}
		
		/* 邮件确认 */
		if(strstr($keyword, '邮件'))
		{
			$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
			mysql_select_db("app_wcjdemo",$link);    //选择数据库
			
			$nowtime=date("Y/m/d H:i:s",time());
			$sql="UPDATE mailcheck SET Mailstatus=0,Time='$nowtime' WHERE Id=1";
			if(!mysql_query($sql,$link)){
				die('Error:'.mysql_error());
			}
			mysql_close($link);
			$contentStr="已安全处理警报！";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
		
		/* 温度 */
		else if(strstr($keyword,'温度')){
			$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
			mysql_select_db("app_wcjdemo",$link);    //选择数据库
	
			//查找温度值
			$result=mysql_query("SELECT * FROM status");
			while($result_array=mysql_fetch_array($result)){
				if($result_array['Id']==1){
					$Temperature=$result_array['Temperature'];
				}
			}
			
			mysql_close($link);
			//输出回应
			$contentStr="报告大王，现在温度为：".$Temperature."℃";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
	
		/* 湿度 */
		else if(strstr($keyword,'湿度')){
			$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
			mysql_select_db("app_wcjdemo",$link);    //选择数据库
	
			//查找湿度值
			$result=mysql_query("SELECT * FROM status");
			while($result_array=mysql_fetch_array($result)){
				if($result_array['Id']==1){
					$Humidity=$result_array['Humidity'];
				}
			}
			
			mysql_close($link);
			//输出回应
			$contentStr="报告大王，现在湿度为：".$Humidity."%";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
	
		/* 开灯 */
		else if(strstr($keyword,'开灯')){
			$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
			mysql_select_db("app_wcjdemo",$link);    //选择数据库
	
			//查找灯的状态
			$result=mysql_query("SELECT * FROM status");
			while($result_array=mysql_fetch_array($result)){
				if($result_array['Id']==1){
					$Ledstatus=$result_array['Ledstatus'];
				}
			}
			
			//开灯指令
			if($Ledstatus==1){
				//输出回应
				$contentStr="报告大王，灯已亮，不需要重复开灯。";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}else{
				//更新数据库
				$nowtime=date("Y/m/d H:i:s",time());
				$sql="UPDATE status SET Led=1,Statustime='$nowtime' WHERE Id=1";
				if(!mysql_query($sql,$link)){
					die('Error:'.mysql_error());
				}
				//输出回应
				$contentStr="报告大王，我已经帮您打开灯了。";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}
			
			mysql_close($link);
		}
	
		/* 关灯 */
		else if(strstr($keyword,'关灯')){
			$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
			mysql_select_db("app_wcjdemo",$link);    //选择数据库
	
			//查找灯的状态
			$result=mysql_query("SELECT * FROM status");
			while($result_array=mysql_fetch_array($result)){
				if($result_array['Id']==1){
					$Ledstatus=$result_array['Ledstatus'];
				}
			}
	
			//关灯指令
			if($Ledstatus==0){
				//输出回应
				$contentStr="报告大王，灯已关，不需要重复关灯。";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}else{
				//更新数据库
				$nowtime=date("Y/m/d H:i:s",time());
				$sql="UPDATE status SET Led=0,Statustime='$nowtime' WHERE Id=1";
				if(!mysql_query($sql,$link)){
					die('Error:'.mysql_error());
				}
				//输出回应
				$contentStr="报告大王，我已经帮您关闭灯了。";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}
			
			mysql_close($link);
		}
	
		/* 查看窗户是否有人 */
		else if(strstr($keyword,'安全')){
			$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
			mysql_select_db("app_wcjdemo",$link);    //选择数据库
	
			//查找湿度值
			$result=mysql_query("SELECT * FROM status");
			while($result_array=mysql_fetch_array($result)){
				if($result_array['Id']==1){
					$Windowstatus=$result_array['Windowstatus'];
				}
			}
			
			mysql_close($link);
			if($Windowstatus==1){
				//输出回应
				$contentStr="报告大王，您的窗户上有人经过，请小心小偷！";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}else{
				//输出回应
				$contentStr="报告大王，您的窗户上没有人经过，家里很安全！";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}
		}
		else{
			$contentStr="抱歉大王，无法识别您的指令：".$keyword;
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}
	}
	
	//验证
	private function testname($fromUsername)
	{
		$link=mysql_connect("localhost","root","dHIoPOi7Ej3n");
		mysql_select_db("app_wcjdemo",$link);    //选择数据库
	
		//查找温度值
		$result=mysql_query("SELECT * FROM users");
		while($result_array=mysql_fetch_array($result)){
			if($result_array['Openid']==$fromUsername){
				$Id=$result_array['Id'];
				}
			}
			
		mysql_close($link);
		if($Id)
			$flag=1;
		else
			$flag=0;
		
		return $flag;
	}

	public function vaildmail()
	{
		$url='http://120.26.103.180/mail/mail.php';
		file_get_contents($url);
		$url='http://120.26.103.180/mail/mailcheck.php';
		file_get_contents($url);
	}
	
}

?>