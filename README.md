# zjulink_v1.0
这个是完成版物联网项目外部1.0版本，实现微信公众平台注册用户远程控制联网的arduino。


/************************************************************************
arduino.ino
`运行于arduino uno r3上，需要的拓展版为核心模块为TI CC3000 WIFI模块
`需要下载Adafruit_CC3000_library arduino拓展库以及DHT11拓展库（这个拓展库比较简单，自己也可以写）
`使用时需要自定义WIFI的名称和密码
`注意CC3000所需电流大于500mA，USB带起来会出现数据发送中断现象，建议外接电源
`具体的电路接法，请详细阅读源代码
***********************************************************************/

/************************************************************************
`服务器所需要环境为apache+mysql，php5.3版本，建议安装phpmyadmin
`服务器文件结构为：
  ../www/login/images/...png
            ../login.html
            ../login.php
            ../index.php
            ../index_ctr.php
      ../mail/mail.php
            ../mailcheck.php
      ../arduino.php
      ../wechat.php
`直接文件结构复制可以直接运行，需要自己更改数据库
***********************************************************************/

/************************************************************************
>>>>>>> origin/master
`数据库名称为app_wcjdemo,有三个表
`表1 status
     Id Led Temperature Humanity Windowstatus Ledstatus Time
`表2 users
     Id Openid Name Key Time
`表3 mailstatus
     Id Mailcheck
<<<<<<< HEAD
***********************************************************************/