-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 01 月 03 日 05:15
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `shuzi`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_ad`
--

CREATE TABLE IF NOT EXISTS `think_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `ad_position_id` varchar(10) DEFAULT NULL COMMENT '广告位',
  `link_url` varchar(128) DEFAULT NULL,
  `images` varchar(128) DEFAULT NULL,
  `start_date` date DEFAULT NULL COMMENT '开始时间',
  `end_date` date DEFAULT NULL COMMENT '结束时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `closed` tinyint(1) DEFAULT '0',
  `orderby` tinyint(3) DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- 转存表中的数据 `think_ad`
--

INSERT INTO `think_ad` (`id`, `title`, `ad_position_id`, `link_url`, `images`, `start_date`, `end_date`, `status`, `closed`, `orderby`) VALUES
(24, '23', '1', '123', '20170416\\363c841674371a9e730e65a085fbdf18.jpg', '0000-00-00', '0000-00-00', 1, 0, 23),
(25, '123', '1', '213', '20170416\\d8f2098b4846f2e087cc2c5dd1575219.jpg', '2016-10-12', '2016-10-12', 1, 0, 100),
(26, '345', '1', '345', '20170416\\f59059c762d959f04f9226eb0c126987.jpg', '2016-10-25', '2016-10-20', 0, 1, 127);

-- --------------------------------------------------------

--
-- 表的结构 `think_admin`
--

CREATE TABLE IF NOT EXISTS `think_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `password` varchar(32) COLLATE utf8_bin NOT NULL COMMENT '密码',
  `portrait` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '头像',
  `loginnum` int(11) DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT '最后登录IP',
  `last_login_time` int(11) DEFAULT '0' COMMENT '最后登录时间',
  `real_name` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '真实姓名',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `groupid` int(11) NOT NULL DEFAULT '1' COMMENT '用户角色id',
  `groupname` varchar(20) COLLATE utf8_bin NOT NULL,
  `token` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `sex` varchar(10) COLLATE utf8_bin NOT NULL COMMENT '性别',
  `mobile` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '手机',
  `tele` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '座机',
  `dept` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '所在部门',
  `position` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '职位',
  `email` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '电子邮箱',
  `wechat` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '微信',
  `remark` text COLLATE utf8_bin COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=49 ;

--
-- 转存表中的数据 `think_admin`
--

INSERT INTO `think_admin` (`id`, `username`, `password`, `portrait`, `loginnum`, `last_login_ip`, `last_login_time`, `real_name`, `status`, `groupid`, `groupname`, `token`, `sex`, `mobile`, `tele`, `dept`, `position`, `email`, `wechat`, `remark`) VALUES
(1, 'admin', '218dbb225911693af03a713581a7227f', '20161122\\admin.jpg', 338, '0.0.0.0', 1514949830, 'admin', 1, 1, '', '1ac2fc424c64cdf80db98a246f439287', '', '', '', '', '', '', '', ''),
(13, 'test', '218dbb225911693af03a713581a7227f', '', 1803, '0.0.0.0', 1514484564, 'test', 1, 4, '', '4ee2e395e9921f515d00599a5f79ae3f', '', '', '', '', '测试', '18055089@qq.com', '', ''),
(48, 'test2', '218dbb225911693af03a713581a7227f', '20171229\\6f9cb6624fcf33a0ad1c1279b94b59b8.png', 0, NULL, 0, '测试员2', 1, 4, '系统测试员', NULL, '男', '15611116916', '7219', '系统测试员', '测试', 'test@bhidi.com', 'wechat123', '备注');

-- --------------------------------------------------------

--
-- 表的结构 `think_ad_position`
--

CREATE TABLE IF NOT EXISTS `think_ad_position` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '分类名称',
  `orderby` varchar(10) DEFAULT '100' COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `think_ad_position`
--

INSERT INTO `think_ad_position` (`id`, `name`, `orderby`, `create_time`, `update_time`, `status`) VALUES
(23, 'aaa', '30', 1501813046, 1501813046, 1),
(22, 'abvc', '15', 1501813036, 1502294001, 1),
(25, '首页banner', '50', 1502181832, 1502181832, 1),
(26, '6168', '11', 1502182772, 1502182772, 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_article`
--

CREATE TABLE IF NOT EXISTS `think_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章逻辑ID',
  `title` varchar(128) NOT NULL COMMENT '文章标题',
  `cate_id` int(11) NOT NULL DEFAULT '1' COMMENT '文章类别',
  `photo` varchar(64) DEFAULT '' COMMENT '文章图片',
  `remark` varchar(256) DEFAULT '' COMMENT '文章描述',
  `keyword` varchar(32) DEFAULT '' COMMENT '文章关键字',
  `content` text NOT NULL COMMENT '文章内容',
  `views` int(11) NOT NULL DEFAULT '1' COMMENT '浏览量',
  `status` tinyint(1) DEFAULT NULL,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '文章类型',
  `is_tui` int(1) DEFAULT '0' COMMENT '是否推荐',
  `from` varchar(16) NOT NULL DEFAULT '' COMMENT '来源',
  `writer` varchar(64) NOT NULL COMMENT '作者',
  `ip` varchar(16) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `a_title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=69 ;

--
-- 转存表中的数据 `think_article`
--

INSERT INTO `think_article` (`id`, `title`, `cate_id`, `photo`, `remark`, `keyword`, `content`, `views`, `status`, `type`, `is_tui`, `from`, `writer`, `ip`, `create_time`, `update_time`) VALUES
(46, 'PHP人民币金额数字转中文大写的函数代码', 5, '20170416\\8b2ef718255d495dc9668f0dec0224af.jpg', '在网上看到一个非常有趣的PHP人民币金额数字转中文大写的函数，其实质就是数字转换成中文大写，测试了一下，非常有趣，随便输个数字，就可以将其大写打印出来，新手朋友们试一下吧', '人民币转大写', '<p>在网上看到一个非常有趣的PHP人民币金额数字转中文大写的函数，其实质就是数字转换成中文大写，测试了一下，非常有趣，随便输个数字，就可以将其大写打印出来，新手朋友们试一下吧</p><pre class="brush:php;toolbar:false">/**\n*数字金额转换成中文大写金额的函数\n*String&nbsp;Int&nbsp;&nbsp;$num&nbsp;&nbsp;要转换的小写数字或小写字符串\n*return&nbsp;大写字母\n*小数位为两位\n**/\nfunction&nbsp;get_amount($num){\n$c1&nbsp;=&nbsp;&quot;零壹贰叁肆伍陆柒捌玖&quot;;\n$c2&nbsp;=&nbsp;&quot;分角元拾佰仟万拾佰仟亿&quot;;\n$num&nbsp;=&nbsp;round($num,&nbsp;2);\n$num&nbsp;=&nbsp;$num&nbsp;*&nbsp;100;\nif&nbsp;(strlen($num)&nbsp;&gt;&nbsp;10)&nbsp;{\nreturn&nbsp;&quot;数据太长，没有这么大的钱吧，检查下&quot;;\n}&nbsp;\n$i&nbsp;=&nbsp;0;\n$c&nbsp;=&nbsp;&quot;&quot;;\nwhile&nbsp;(1)&nbsp;{\nif&nbsp;($i&nbsp;==&nbsp;0)&nbsp;{\n$n&nbsp;=&nbsp;substr($num,&nbsp;strlen($num)-1,&nbsp;1);\n}&nbsp;else&nbsp;{\n$n&nbsp;=&nbsp;$num&nbsp;%&nbsp;10;\n}&nbsp;\n$p1&nbsp;=&nbsp;substr($c1,&nbsp;3&nbsp;*&nbsp;$n,&nbsp;3);\n$p2&nbsp;=&nbsp;substr($c2,&nbsp;3&nbsp;*&nbsp;$i,&nbsp;3);\nif&nbsp;($n&nbsp;!=&nbsp;&#39;0&#39;&nbsp;||&nbsp;($n&nbsp;==&nbsp;&#39;0&#39;&nbsp;&amp;&amp;&nbsp;($p2&nbsp;==&nbsp;&#39;亿&#39;&nbsp;||&nbsp;$p2&nbsp;==&nbsp;&#39;万&#39;&nbsp;||&nbsp;$p2&nbsp;==&nbsp;&#39;元&#39;)))&nbsp;{\n$c&nbsp;=&nbsp;$p1&nbsp;.&nbsp;$p2&nbsp;.&nbsp;$c;\n}&nbsp;else&nbsp;{\n$c&nbsp;=&nbsp;$p1&nbsp;.&nbsp;$c;\n}&nbsp;\n$i&nbsp;=&nbsp;$i&nbsp;+&nbsp;1;\n$num&nbsp;=&nbsp;$num&nbsp;/&nbsp;10;\n$num&nbsp;=&nbsp;(int)$num;\nif&nbsp;($num&nbsp;==&nbsp;0)&nbsp;{\nbreak;\n}&nbsp;\n}\n$j&nbsp;=&nbsp;0;\n$slen&nbsp;=&nbsp;strlen($c);\nwhile&nbsp;($j&nbsp;&lt;&nbsp;$slen)&nbsp;{\n$m&nbsp;=&nbsp;substr($c,&nbsp;$j,&nbsp;6);\nif&nbsp;($m&nbsp;==&nbsp;&#39;零元&#39;&nbsp;||&nbsp;$m&nbsp;==&nbsp;&#39;零万&#39;&nbsp;||&nbsp;$m&nbsp;==&nbsp;&#39;零亿&#39;&nbsp;||&nbsp;$m&nbsp;==&nbsp;&#39;零零&#39;)&nbsp;{\n$left&nbsp;=&nbsp;substr($c,&nbsp;0,&nbsp;$j);\n$right&nbsp;=&nbsp;substr($c,&nbsp;$j&nbsp;+&nbsp;3);\n$c&nbsp;=&nbsp;$left&nbsp;.&nbsp;$right;\n$j&nbsp;=&nbsp;$j-3;\n$slen&nbsp;=&nbsp;$slen-3;\n}&nbsp;\n$j&nbsp;=&nbsp;$j&nbsp;+&nbsp;3;\n}&nbsp;\nif&nbsp;(substr($c,&nbsp;strlen($c)-3,&nbsp;3)&nbsp;==&nbsp;&#39;零&#39;)&nbsp;{\n$c&nbsp;=&nbsp;substr($c,&nbsp;0,&nbsp;strlen($c)-3);\n}\nif&nbsp;(empty($c))&nbsp;{\nreturn&nbsp;&quot;零元整&quot;;\n}else{\nreturn&nbsp;$c&nbsp;.&nbsp;&quot;整&quot;;\n}\n}</pre><p>最终实现效果：</p><p><img src="/Uploads/ueditor/2015-12-28/1451310141372440.png" title="1451310141372440.png" alt="1449026968974428.png"/></p>', 1, 1, 1, 1, 'Win 8.1', '轮回', '124.152.7.106', 1449026848, 1492346057),
(47, 'Windows下mysql忘记密码的解决方法', 1, '20170416\\f5f5aacefa23b9efb1c81895cb932572.jpg', 'Windows下mysql忘记密码的解决方法', 'mysql', '<p>方法一：</p><p>1、在DOS窗口下输入</p><pre>net&nbsp;stop&nbsp;mysql5</pre><p>&nbsp;</p><p>或</p><pre>net&nbsp;stop&nbsp;mysql</pre><p>&nbsp;</p><p>2、开一个DOS窗口，这个需要切换到mysql的bin目录。<br/>一般在bin目录里面创建一个批处理1.bat,内容是cmd.exe运行一下即可就切换到当前目录，然后输入</p><pre>mysqld-nt&nbsp;--skip-grant-tables;</pre><p>&nbsp;</p><p>3、再开一个DOS窗口</p><pre>mysql&nbsp;-u&nbsp;root</pre><p>&nbsp;</p><p>4、输入：</p><pre>use&nbsp;mysql&nbsp;\nupdate&nbsp;user&nbsp;set&nbsp;password=password(&quot;new_pass&quot;)&nbsp;where&nbsp;user=&quot;root&quot;;&nbsp;\nflush&nbsp;privileges;&nbsp;\nexit</pre><p>&nbsp;</p><p>5、使用任务管理器，找到mysqld-nt的进程，结束进程&nbsp;<br/>或下面的步骤<br/>1，停止MYSQL服务，CMD打开DOS窗口，输入 net stop mysql&nbsp;<br/>2，在CMD命令行窗口，进入MYSQL安装目录 比如E:Program FilesMySQLMySQL Server 5.0bin&nbsp;<br/>示范命令:&nbsp;<br/>输入 e:回车,&nbsp;<br/>输入cd &quot;E:Program FilesMySQLMySQL Server 5.0bin&quot;&nbsp;<br/>注意双引号也要输入,这样就可以进入Mysql安装目录了.&nbsp;<br/>3，进入mysql安全模式，即当mysql起来后，不用输入密码就能进入数据库。&nbsp;<br/>命令为：</p><pre>mysqld-nt&nbsp;--skip-grant-tables</pre><p>&nbsp;</p><p>4，重新打开一个CMD命令行窗口，输入</p><p>mysql -uroot -p，使用空密码的方式登录MySQL（不用输入密码，直接按回车）</p><p>5，输入以下命令开始修改root用户的密码（注意：命令中mysql.user中间有个“点”）</p><p>mysql.user：数据库名.表名<br/>mysql&gt; update mysql.user set password=PASSWORD(&#39;新密码&#39;) where User=&#39;root&#39;;&nbsp;<br/>6，刷新权限表&nbsp;<br/>mysql&gt; flush privileges;&nbsp;<br/>7，退出&nbsp;<br/>mysql&gt; quit</p><p><br/>这样MYSQL超级管理员账号 ROOT已经重新设置好了，接下来 在任务管理器里结束掉 mysql-nt.exe 这个进程，重新启动MYSQL即可！</p><p>（也可以直接重新启动服务器）&nbsp;<br/>MYSQL重新启动后，就可以用新设置的ROOT密码登陆MYSQL了！</p><p>方法二：</p><p>首先在 MySQL的安装目录下 新建一个pwdhf.txt, 输入文本：</p><pre>SET&nbsp;PASSWORD&nbsp;FOR&nbsp;&#39;root&#39;@&#39;localhost&#39;&nbsp;=&nbsp;PASSWORD(&#39;*****&#39;);</pre><p>&nbsp;</p><p>红色部份为 需要设置的新密码&nbsp;<br/>用windows服务管理工具或任务管理器来停止MySQL服务 (任务管理器K掉 mysqld-nt 进程)&nbsp;<br/>Dos命令提示符到 MySQL安装目录下的bin目录 如我的是</p><p>D:Program FilesMySQLMySQL Server 5.1bin&nbsp;<br/>然后运行：</p><pre>mysqld-nt&nbsp;--init-file=../pwdhf.txt</pre><p>&nbsp;</p><p>执行完毕， 停止MySQL数据库服务 (任务管理器K掉 mysqld-nt 进程)，然后再重新以正常模式启动MYSQL 即可</p><hr style="color: rgb(51, 51, 51); font-family: Arial; font-size: 14px; line-height: 26px; white-space: normal; background-color: rgb(255, 255, 255);"/><p>mysql5.1或以上</p><p>1、 首先检查mysql服务是否启动，若已启动则先将其停止服务，可在开始菜单的运行，使用命令：</p><pre>net&nbsp;stop&nbsp;mysql</pre><p>&nbsp;</p><p>2、打开第一个cmd窗口，切换到mysql的bin目录，运行命令：</p><pre>mysqld&nbsp;--defaults-file=&quot;C:Program&nbsp;FilesMySQLMySQL&nbsp;Server&nbsp;5.1my.ini&quot;&nbsp;--console&nbsp;--skip-grant-tables</pre><p>&nbsp;</p><p>注释：</p><p>该命令通过跳过权限安全检查，开启mysql服务，这样连接mysql时，可以不用输入用户密码。&nbsp;<br/>&nbsp;</p><p>&nbsp;</p><p>3、打开第二个cmd窗口，连接mysql：</p><p>输入命令：</p><pre>mysql&nbsp;-uroot&nbsp;-p</pre><p>出现：</p><p>Enter password:</p><p>在这里直接回车，不用输入密码。</p><p>然后就就会出现登录成功的信息，</p><p>&nbsp;</p><p>&nbsp;</p><p>4、使用命令：</p><pre>show&nbsp;databases;</pre><p>&nbsp;</p><p>&nbsp;</p><p>5、使用命令切换到mysql数据库：</p><pre>use&nbsp;mysql;</pre><p>&nbsp;</p><p>6、使用命令更改root密码为123456：</p><pre>UPDATE&nbsp;user&nbsp;SET&nbsp;Password=PASSWORD(&#39;123456&#39;)&nbsp;where&nbsp;USER=&#39;root&#39;;</pre><p>&nbsp;</p><p>&nbsp;</p><p>7、刷新权限：</p><pre>FLUSH&nbsp;PRIVILEGES;</pre><p>&nbsp;</p><p>8、然后退出，重新登录：</p><p>quit</p><p>重新登录：</p><pre>mysql&nbsp;-uroot&nbsp;-p</pre><p>&nbsp;</p><p>9、出现输入密码提示，输入新的密码即可登录：</p><p>Enter password: ***********</p><p>显示登录信息： 成功&nbsp; 就一切ok了</p><p>&nbsp;</p><p>10、重新启动mysql服务</p><pre>net&nbsp;start&nbsp;mysql</pre><p><br/></p>', 1, 0, 0, 0, 'Win 8.1', '轮回', '0.0.0.0', 1450339377, 1492346047),
(48, '禁止网页复制的代码', 1, '20170416\\c3646031ca540e4217d1228eefe99c4c.jpg', '禁止网页复制的代码', '网页复制', '<p>今天做一网站项目时，客户要求让用户不能复制网站内容，网上搜索了一下，总结成以下二几行代码。其实吧，要是懂的人，这些都是浮云来的，客户就是要让一般人不能复制他的内容资料。</p><pre class="brush:html;toolbar:false" style="box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; padding: 9.5px; list-style: none; border: 1px solid rgb(204, 204, 204); overflow: auto; font-family: Menlo, Monaco, Consolas, &#39;Courier New&#39;, monospace; font-size: 13px; line-height: 1.42857; color: rgb(51, 51, 51); word-break: break-all; word-wrap: break-word; border-radius: 4px; background-color: rgb(245, 245, 245);">&quot;&nbsp;_ue_custom_node_=&quot;true&quot;&gt;&lt;\ntitle\n&gt;禁止网页复制的代码&nbsp;&nbsp;网页禁止右键、禁止查看源代码、禁止复制的代码，试试你的右键、ctrl+c和ctrl+c吧~\n&nbsp;&nbsp;\n&nbsp;&nbsp;&quot;&nbsp;_ue_custom_node_=&quot;true&quot;&gt;</pre><p><br/></p>', 1, 0, 1, 1, 'Win 8.1', '轮回', '0.0.0.0', 1450340150, 1492346038),
(49, '如何使用谷歌字体', 1, '20170416\\970c587b487610a793857bc161773f2a.jpg', '如何使用谷歌字体', '谷歌字体', '<p style="text-align:center"><img src="/Uploads/ueditor/2015-12-28/1451233062718458.png" title="1451233062718458.png" alt="QQ截图20151228001616.png"/></p><p style="padding: 0px; margin-top: 8px; margin-bottom: 8px; line-height: 22.5px; letter-spacing: 0.5px; font-size: 12.5px; white-space: normal; word-wrap: break-word; word-break: break-all; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, Verdana, sans-serif, 宋体; background-color: rgb(255, 255, 255);">国内网站使用谷歌字体是不方便的，解决办法如下<br style="padding: 0px; margin: 0px;"/></p><p style="padding: 0px; margin-top: 8px; margin-bottom: 8px; line-height: 22.5px; letter-spacing: 0.5px; font-size: 12.5px; white-space: normal; word-wrap: break-word; word-break: break-all; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, Verdana, sans-serif, 宋体; background-color: rgb(255, 255, 255);">1、将谷歌的地址换成360的</p><p style="padding: 0px; margin-top: 8px; margin-bottom: 8px; line-height: 22.5px; letter-spacing: 0.5px; font-size: 12.5px; white-space: normal; word-wrap: break-word; word-break: break-all; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, Verdana, sans-serif, 宋体; background-color: rgb(255, 255, 255);"><a href="http://fonts.googleapis.com/" rel="nofollow" style="padding: 0px; margin: 0px; color: rgb(255, 131, 115); outline: 0px; font-size: 12px;">http://fonts.googleapis.com</a>&nbsp;换成&nbsp;<a href="http://fonts.useso.com/" rel="nofollow" style="padding: 0px; margin: 0px; color: rgb(255, 131, 115); outline: 0px; font-size: 12px;">http://fonts.useso.com</a></p><p style="padding: 0px; margin-top: 8px; margin-bottom: 8px; line-height: 22.5px; letter-spacing: 0.5px; font-size: 12.5px; white-space: normal; word-wrap: break-word; word-break: break-all; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, Verdana, sans-serif, 宋体; background-color: rgb(255, 255, 255);">2、只做了第一步还是有问题的，会报错：</p><p style="padding: 0px; margin-top: 8px; margin-bottom: 8px; line-height: 22.5px; letter-spacing: 0.5px; font-size: 12.5px; white-space: normal; word-wrap: break-word; word-break: break-all; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, Verdana, sans-serif, 宋体; background-color: rgb(255, 255, 255);">No &#39;Access-Control-Allow-Origin&#39; header is present on the requested resource</p><p style="padding: 0px; margin-top: 8px; margin-bottom: 8px; line-height: 22.5px; letter-spacing: 0.5px; font-size: 12.5px; white-space: normal; word-wrap: break-word; word-break: break-all; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, Verdana, sans-serif, 宋体; background-color: rgb(255, 255, 255);">解决办法是给html页面添加头信息</p><p style="padding: 0px; margin-top: 8px; margin-bottom: 8px; line-height: 22.5px; letter-spacing: 0.5px; font-size: 12.5px; white-space: normal; word-wrap: break-word; word-break: break-all; color: rgb(51, 51, 51); font-family: &#39;Microsoft YaHei&#39;, Verdana, sans-serif, 宋体; background-color: rgb(255, 255, 255);"><span style="padding: 0px; margin: 0px; color: rgb(70, 70, 70); font-family: Arial，; font-size: 14px; line-height: 21px;"><meta http-equiv="Access-Control-Allow-Origin" content="*"/></span></p><p><br/></p>', 1, 0, 0, 0, 'Win 8.1', '轮回', '0.0.0.0', 1450764484, 1492346031),
(50, 'winForm窗体关闭按钮实现托盘后台运行（类似QQ托盘区运行）', 4, '20170416\\50929a5bfd0a8ecd4e0883172c9814cc.jpg', '今天遇到了一个需求，如果用户不小心点击了“关闭”按钮，但是他的本意不是想要真的关闭这个窗体。 对这个需求完全可以在单击“关闭”按钮的时候弹出一个对话框，来让用户确定是否真的要退出。这是一个很好的解决方法，并且实现也是很容易的。但是人家不想这样，想要拥有类似QQ在托盘区后台运行的那种效果，没办法，只能想办法来实现了。', 'winForm', '<p>今天遇到了一个需求，如果用户不小心点击了“关闭”按钮，但是他的本意不是想要真的关闭这个窗体。</p><p>&nbsp;</p><p>对这个需求完全可以在单击“关闭”按钮的时候弹出一个对话框，来让用户确定是否真的要退出。这是一个很好的解决方法，并且实现也是很容易的。但是人家不想这样，想要拥有类似QQ在托盘区后台运行的那种效果，没办法，只能想办法来实现了。</p><p>&nbsp;</p><p>其实，这个在网上也有很多的实现方法，但是我试了试有些直接复制过来并不能直接运行，或者能运行的吧又没有注释。今天在这里就给大家贴一下我的代码，也有不足之处，希望大家给出意见.</p><p style="text-align:center"><img src="/Uploads/ueditor/2015-12-28/1451309750351569.png" title="1451309750351569.png" alt="1450926662631174.png"/></p><pre class="brush:c#;toolbar:false">using&nbsp;System;\nusing&nbsp;System.Collections.Generic;\nusing&nbsp;System.ComponentModel;\nusing&nbsp;System.Data;\nusing&nbsp;System.Drawing;\nusing&nbsp;System.Linq;\nusing&nbsp;System.Text;\nusing&nbsp;System.Windows.Forms;\nusing&nbsp;System.Windows;\nnamespace&nbsp;winform窗体托盘后台运行\n{\n&nbsp;&nbsp;&nbsp;&nbsp;public&nbsp;partial&nbsp;class&nbsp;Form1&nbsp;:&nbsp;Form&nbsp;\n&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//这里在窗体上没有拖拽一个NotifyIcon控件，而是在这里定义了一个变量\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;private&nbsp;NotifyIcon&nbsp;notifyIcon&nbsp;=&nbsp;null;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;public&nbsp;Form1()\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;InitializeComponent();\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//调用初始化托盘显示函数\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;InitialTray();\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;private&nbsp;void&nbsp;Form1_Load(object&nbsp;sender,&nbsp;EventArgs&nbsp;e)\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//这里写其他代码\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;窗体关闭的单击事件\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;private&nbsp;void&nbsp;Form1_FormClosing(object&nbsp;sender,&nbsp;FormClosingEventArgs&nbsp;e)\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e.Cancel&nbsp;=&nbsp;true;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//通过这里可以看出，这里的关闭其实不是真正意义上的“关闭”，而是将窗体隐藏，实现一个“伪关闭”\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;this.Hide();\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;private&nbsp;void&nbsp;InitialTray()\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//隐藏主窗体\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;this.Hide();\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//实例化一个NotifyIcon对象\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon&nbsp;=&nbsp;new&nbsp;NotifyIcon();\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//托盘图标气泡显示的内容\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon.BalloonTipText&nbsp;=&nbsp;&quot;正在后台运行&quot;;&nbsp;&nbsp;&nbsp;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//托盘图标显示的内容\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon.Text&nbsp;=&nbsp;&quot;窗体托盘后台运行测试程序&quot;;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//注意：下面的路径可以是绝对路径、相对路径。但是需要注意的是：文件必须是一个.ico格式\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon.Icon&nbsp;=&nbsp;new&nbsp;System.Drawing.Icon(&quot;E:/ASP项目/images/3.5&nbsp;inch&nbsp;Floppy.ico&quot;);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//true表示在托盘区可见，false表示在托盘区不可见\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon.Visible&nbsp;=&nbsp;true;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//气泡显示的时间（单位是毫秒）\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon.ShowBalloonTip(2000);&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon.MouseClick&nbsp;+=&nbsp;new&nbsp;System.Windows.Forms.MouseEventHandler(notifyIcon_MouseClick);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;////设置二级菜单\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//MenuItem&nbsp;setting1&nbsp;=&nbsp;new&nbsp;MenuItem(&quot;二级菜单1&quot;);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//MenuItem&nbsp;setting2&nbsp;=&nbsp;new&nbsp;MenuItem(&quot;二级菜单2&quot;);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//MenuItem&nbsp;setting&nbsp;=&nbsp;new&nbsp;MenuItem(&quot;一级菜单&quot;,&nbsp;new&nbsp;MenuItem[]{setting1,setting2});\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//帮助选项，这里只是“有名无实”在菜单上只是显示，单击没有效果，可以参照下面的“退出菜单”实现单击事件\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MenuItem&nbsp;help&nbsp;=&nbsp;new&nbsp;MenuItem(&quot;帮助&quot;);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//关于选项\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MenuItem&nbsp;about&nbsp;=&nbsp;new&nbsp;MenuItem(&quot;关于&quot;);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//退出菜单项\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MenuItem&nbsp;exit&nbsp;=&nbsp;new&nbsp;MenuItem(&quot;退出&quot;);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;exit.Click&nbsp;+=&nbsp;new&nbsp;EventHandler(exit_Click);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;////关联托盘控件\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//注释的这一行与下一行的区别就是参数不同，setting这个参数是为了实现二级菜单\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//MenuItem[]&nbsp;childen&nbsp;=&nbsp;new&nbsp;MenuItem[]&nbsp;{&nbsp;setting,&nbsp;help,&nbsp;about,&nbsp;exit&nbsp;};\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MenuItem[]&nbsp;childen&nbsp;=&nbsp;new&nbsp;MenuItem[]&nbsp;{help,about,exit};\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notifyIcon.ContextMenu&nbsp;=&nbsp;new&nbsp;ContextMenu(childen);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//窗体关闭时触发\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;this.FormClosing&nbsp;+=&nbsp;new&nbsp;System.Windows.Forms.FormClosingEventHandler(this.Form1_FormClosing);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;鼠标单击\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;private&nbsp;void&nbsp;notifyIcon_MouseClick(object&nbsp;sender,&nbsp;System.Windows.Forms.MouseEventArgs&nbsp;e)\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//鼠标左键单击\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if&nbsp;(e.Button&nbsp;==&nbsp;MouseButtons.Left)\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//如果窗体是可见的，那么鼠标左击托盘区图标后，窗体为不可见\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if&nbsp;(this.Visible&nbsp;==&nbsp;true&nbsp;)\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;this.Visible&nbsp;=&nbsp;false;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;else\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;this.Visible&nbsp;=&nbsp;true;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;this.Activate();\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;退出选项\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;///&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;private&nbsp;void&nbsp;exit_Click(object&nbsp;sender,&nbsp;EventArgs&nbsp;e)\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//退出程序\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;System.Environment.Exit(0);&nbsp;&nbsp;\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}\n&nbsp;&nbsp;&nbsp;&nbsp;}\n}</pre><p><br/></p>', 1, 0, 0, 0, 'Win 8.1', '轮回', '124.152.7.106', 1450926579, 1492346022),
(67, '太难', 2, '20170810\\cd115e0dd64732861ad62ebd75fd5e15.jpg', '', 'PHP', '<p>W 发士大夫但是</p>', 1, 1, 1, 0, '', '', '', 1501597084, 1502341187),
(68, '54254254', 2, '20170824\\dfade61edda20cfd4e10962259466150.png', '5254', '242424', '<p><br/></p><p>2145254254254</p>', 1, 1, 1, 1, '', '', '', 1503569472, 1503569472);

-- --------------------------------------------------------

--
-- 表的结构 `think_article_cate`
--

CREATE TABLE IF NOT EXISTS `think_article_cate` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '分类名称',
  `orderby` varchar(10) DEFAULT '100' COMMENT '排序',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `think_article_cate`
--

INSERT INTO `think_article_cate` (`id`, `name`, `orderby`, `create_time`, `update_time`, `status`) VALUES
(1, '大鼻孔', '1', 1477140627, 1502266891, 1),
(2, '生活随笔', '2', 1477140627, 1477140627, 0),
(3, '热点分享', '3', 1477140604, 1477140627, 0),
(4, '.NET', '4', 1477140627, 1477140627, 1),
(5, 'PHP', '5', 1477140627, 1477140627, 0),
(6, 'Java', '6', 1477140627, 1477140627, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_auth_group`
--

CREATE TABLE IF NOT EXISTS `think_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text NOT NULL,
  `pid` int(11) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `think_auth_group`
--

INSERT INTO `think_auth_group` (`id`, `title`, `status`, `rules`, `pid`, `create_time`, `update_time`) VALUES
(1, '超级管理员', 1, '', 0, 1446535750, 1446535750),
(4, '系统测试员', 1, '1,2,9,85,3,30,4,39,61,62,5,6,7,27,29,13,14,22,24,25,40,41,43,26,44,45,47,48,49,50,51,52,53,54,55,56,57,58,70,71,72,73,80,75,76,77,79', 0, 1446535750, 1513147817);

-- --------------------------------------------------------

--
-- 表的结构 `think_auth_group_access`
--

CREATE TABLE IF NOT EXISTS `think_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `think_auth_group_access`
--

INSERT INTO `think_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(13, 4),
(35, 0),
(38, 15),
(39, 0),
(40, 0),
(41, 0),
(43, 0),
(45, 0),
(48, 4);

-- --------------------------------------------------------

--
-- 表的结构 `think_auth_rule`
--

CREATE TABLE IF NOT EXISTS `think_auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `css` varchar(20) NOT NULL COMMENT '样式',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父栏目ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- 转存表中的数据 `think_auth_rule`
--

INSERT INTO `think_auth_rule` (`id`, `name`, `title`, `type`, `status`, `css`, `condition`, `pid`, `sort`, `create_time`, `update_time`) VALUES
(1, '#', '系统管理', 1, 1, 'fa fa-gear', '', 0, 1, 1446535750, 1477312169),
(2, 'admin/user/index', '用户管理', 1, 1, '', '', 1, 10, 1446535750, 1477312169),
(3, 'admin/role/index', '组织管理', 1, 1, '', '', 1, 20, 1446535750, 1514484154),
(4, 'admin/menu/index', '权限列表', 1, 1, '', '', 1, 30, 1446535750, 1514485985),
(5, '#', '数据库管理', 1, 1, 'fa fa-database', '', 0, 2, 1446535750, 1477312169),
(6, 'admin/data/index', '数据库备份', 1, 1, '', '', 5, 50, 1446535750, 1477312169),
(7, 'admin/data/optimize', '优化表', 1, 1, '', '', 6, 50, 1477312169, 1477312169),
(8, 'admin/data/repair', '修复表', 1, 1, '', '', 6, 50, 1477312169, 1477312169),
(9, 'admin/user/useradd', '添加用户', 1, 1, '', '', 2, 50, 1477312169, 1477312169),
(10, 'admin/user/useredit', '编辑用户', 1, 1, '', '', 2, 50, 1477312169, 1477312169),
(11, 'admin/user/userdel', '删除用户', 1, 1, '', '', 2, 50, 1477312169, 1477312169),
(12, 'admin/user/user_state', '用户状态', 1, 1, '', '', 2, 50, 1477312169, 1477312169),
(13, '#', '日志管理', 1, 1, 'fa fa-tasks', '', 0, 6, 1477312169, 1477312169),
(14, 'admin/log/operate_log', '行为日志', 1, 1, '', '', 13, 50, 1477312169, 1477312169),
(22, 'admin/log/del_log', '删除日志', 1, 1, '', '', 14, 50, 1477312169, 1477316778),
(24, '#', '文章管理', 1, 0, 'fa fa-paste', '', 0, 4, 1477312169, 1477312169),
(25, 'admin/article/index_cate', '文章分类', 1, 1, '', '', 24, 10, 1477312260, 1477312260),
(26, 'admin/article/index', '文章列表', 1, 1, '', '', 24, 20, 1477312333, 1477312333),
(27, 'admin/data/import', '数据库还原', 1, 1, '', '', 5, 50, 1477639870, 1477639870),
(28, 'admin/data/revert', '还原', 1, 1, '', '', 27, 50, 1477639972, 1477639972),
(29, 'admin/data/del', '删除', 1, 1, '', '', 27, 50, 1477640011, 1477640011),
(30, 'admin/role/roleAdd', '添加组织', 1, 1, '', '', 3, 50, 1477640011, 1514484176),
(31, 'admin/role/roleEdit', '编辑组织', 1, 1, '', '', 3, 50, 1477640011, 1514484185),
(32, 'admin/role/roleDel', '删除组织', 1, 1, '', '', 3, 50, 1477640011, 1514484193),
(33, 'admin/role/role_state', '组织状态', 1, 1, '', '', 3, 50, 1477640011, 1514484165),
(34, 'admin/role/giveAccess', '权限分配', 1, 1, '', '', 3, 50, 1477640011, 1477640011),
(35, 'admin/menu/add_rule', '添加权限', 1, 1, '', '', 4, 50, 1477640011, 1514486002),
(36, 'admin/menu/edit_rule', '编辑权限', 1, 1, '', '', 4, 50, 1477640011, 1514486009),
(37, 'admin/menu/del_rule', '删除权限', 1, 1, '', '', 4, 50, 1477640011, 1514486017),
(38, 'admin/menu/rule_state', '权限状态', 1, 1, '', '', 4, 50, 1477640011, 1514486054),
(39, 'admin/menu/ruleorder', '权限排序', 1, 1, '', '', 4, 50, 1477640011, 1514486046),
(40, 'admin/article/add_cate', '添加分类', 1, 1, '', '', 25, 50, 1477640011, 1477640011),
(41, 'admin/article/edit_cate', '编辑分类', 1, 1, '', '', 25, 50, 1477640011, 1477640011),
(42, 'admin/article/del_cate', '删除分类', 1, 1, '', '', 25, 50, 1477640011, 1477640011),
(43, 'admin/article/cate_state', '分类状态', 1, 1, '', '', 25, 50, 1477640011, 1477640011),
(44, 'admin/article/add_article', '添加文章', 1, 1, '', '', 26, 50, 1477640011, 1477640011),
(45, 'admin/article/edit_article', '编辑文章', 1, 1, '', '', 26, 50, 1477640011, 1477640011),
(46, 'admin/article/del_article', '删除文章', 1, 1, '', '', 26, 50, 1477640011, 1477640011),
(47, 'admin/article/article_state', '文章状态', 1, 1, '', '', 26, 50, 1477640011, 1477640011),
(48, '#', '广告管理', 1, 0, 'fa fa-image', '', 0, 5, 1477640011, 1477640011),
(49, 'admin/ad/index_position', '广告位', 1, 1, '', '', 48, 10, 1477640011, 1477640011),
(50, 'admin/ad/add_position', '添加广告位', 1, 1, '', '', 49, 50, 1477640011, 1477640011),
(51, 'admin/ad/edit_position', '编辑广告位', 1, 1, '', '', 49, 50, 1477640011, 1477640011),
(52, 'admin/ad/del_position', '删除广告位', 1, 1, '', '', 49, 50, 1477640011, 1477640011),
(53, 'admin/ad/position_state', '广告位状态', 1, 1, '', '', 49, 50, 1477640011, 1477640011),
(54, 'admin/ad/index', '广告列表', 1, 0, '', '', 48, 20, 1477640011, 1477640011),
(55, 'admin/ad/add_ad', '添加广告', 1, 1, '', '', 54, 50, 1477640011, 1477640011),
(56, 'admin/ad/edit_ad', '编辑广告', 1, 1, '', '', 54, 50, 1477640011, 1477640011),
(57, 'admin/ad/del_ad', '删除广告', 1, 1, '', '', 54, 50, 1477640011, 1477640011),
(58, 'admin/ad/ad_state', '广告状态', 1, 1, '', '', 54, 50, 1477640011, 1477640011),
(83, '#', '示例', 1, 0, 'fa fa-paper-plane', '', 0, 50, 1505281878, 1505281878),
(84, 'admin/demo/sms', '发送短信', 1, 1, '', '', 83, 50, 1505281944, 1505281944),
(61, 'admin/config/index', '配置管理', 1, 1, '', '', 1, 50, 1479908607, 1479908607),
(62, 'admin/config/index', '配置列表', 1, 1, '', '', 61, 50, 1479908607, 1487943813),
(63, 'admin/config/save', '保存配置', 1, 1, '', '', 61, 50, 1479908607, 1487943831),
(70, '#', '会员管理', 1, 0, 'fa fa-users', '', 0, 3, 1484103066, 1484103066),
(72, 'admin/member/add_group', '添加会员组', 1, 1, '', '', 71, 50, 1484103304, 1484103304),
(71, 'admin/member/group', '会员组', 1, 1, '', '', 70, 10, 1484103304, 1484103304),
(73, 'admin/member/edit_group', '编辑会员组', 1, 1, '', '', 71, 50, 1484103304, 1484103304),
(74, 'admin/member/del_group', '删除会员组', 1, 1, '', '', 71, 50, 1484103304, 1484103304),
(75, 'admin/member/index', '会员列表', 1, 1, '', '', 70, 20, 1484103304, 1484103304),
(76, 'admin/member/add_member', '添加会员', 1, 1, '', '', 75, 50, 1484103304, 1484103304),
(77, 'admin/member/edit_member', '编辑会员', 1, 1, '', '', 75, 50, 1484103304, 1484103304),
(78, 'admin/member/del_member', '删除会员', 1, 1, '', '', 75, 50, 1484103304, 1484103304),
(79, 'admin/member/member_status', '会员状态', 1, 1, '', '', 75, 50, 1484103304, 1487937671),
(80, 'admin/member/group_status', '会员组状态', 1, 1, '', '', 71, 50, 1484103304, 1484103304),
(85, 'admin/user/getTreeData', '组织机构', 1, 1, '', '', 2, 50, 1513147269, 1513147269),
(86, 'admin/contract/index', '合同信息', 1, 1, '', '', 1, 50, 1513299208, 1513299208),
(87, 'admin/project/index', '工程划分', 1, 1, '', '', 1, 50, 1513317484, 1513317484),
(88, '#', '质量管理', 1, 1, 'fa fa-star', '', 0, 50, 1514250724, 1514250944),
(89, 'admin/construction/index', '关键施工部位', 1, 1, '', '', 88, 50, 1514251349, 1514251349),
(90, 'admin/acceptance/index', '质量验收管理', 1, 1, '', '', 88, 50, 1514251439, 1514251439),
(91, 'admin/prototype/index', '样板工程', 1, 1, '', '', 88, 50, 1514251526, 1514251526),
(92, 'admin/process/index', '标准工艺', 1, 1, '', '', 88, 50, 1514251565, 1514251581),
(95, 'admin/qc/index', 'QC管理', 1, 1, '', '', 88, 50, 1514251707, 1514251707),
(97, 'admin/acceptance/info', '质量验收信息', 1, 1, '', '', 90, 50, 1514251821, 1514251821),
(96, 'admin/reform/index', '质量问题整改', 1, 1, '', '', 88, 50, 1514251730, 1514251730),
(98, 'admin/acceptance/warning', '质量验收预警', 1, 1, '', '', 90, 50, 1514251874, 1514251874),
(99, 'admin/contract/contractAdd', '添加合同', 1, 1, '', '', 86, 50, 1514486140, 1514486140);

-- --------------------------------------------------------

--
-- 表的结构 `think_config`
--

CREATE TABLE IF NOT EXISTS `think_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `value` text COMMENT '配置值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `think_config`
--

INSERT INTO `think_config` (`id`, `name`, `value`) VALUES
(1, 'web_site_title', '金寨'),
(2, 'web_site_description', '金寨'),
(3, 'web_site_keyword', '金寨'),
(4, 'web_site_icp', '京ICP备15002349号'),
(5, 'web_site_cnzz', ''),
(6, 'web_site_copy', 'Copyright © 2017 北京院 All rights reserved.'),
(7, 'web_site_close', '1'),
(8, 'list_rows', '10'),
(9, 'admin_allow_ip', NULL),
(10, 'alisms_appkey', ''),
(11, 'alisms_appsecret', ''),
(12, 'alisms_signname', '');

-- --------------------------------------------------------

--
-- 表的结构 `think_contract`
--

CREATE TABLE IF NOT EXISTS `think_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hetong_name` varchar(255) NOT NULL COMMENT '合同名称',
  `sn` varchar(255) NOT NULL COMMENT '合同编号',
  `biaoduan_name` varchar(255) NOT NULL COMMENT '标段名称',
  `biaoduan_sn` varchar(255) NOT NULL,
  `xiangmu_name` varchar(255) NOT NULL COMMENT '项目名称',
  `qianding_date` varchar(255) NOT NULL COMMENT '签订日期',
  `kaigong_date` varchar(255) NOT NULL COMMENT '开工日期',
  `contract_time` varchar(255) NOT NULL COMMENT '合同工期',
  `warranty_time` varchar(255) NOT NULL COMMENT '质保期',
  `money` varchar(255) NOT NULL COMMENT '合同金额',
  `first_party` varchar(255) NOT NULL,
  `second_party` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_log`
--

CREATE TABLE IF NOT EXISTS `think_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `admin_name` varchar(50) DEFAULT NULL COMMENT '用户姓名',
  `description` varchar(300) DEFAULT NULL COMMENT '描述',
  `ip` char(60) DEFAULT NULL COMMENT 'IP地址',
  `status` tinyint(1) DEFAULT NULL COMMENT '1 成功 2 失败',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4550 ;

--
-- 转存表中的数据 `think_log`
--

INSERT INTO `think_log` (`log_id`, `admin_id`, `admin_name`, `description`, `ip`, `status`, `add_time`) VALUES
(4337, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503469529),
(4338, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503469560),
(4339, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503469632),
(4340, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503469748),
(4341, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503469749),
(4342, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503469801),
(4343, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503469853),
(4344, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503470004),
(4345, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503470488),
(4346, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503473610),
(4347, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1503569426),
(4348, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1505098116),
(4349, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1505281421),
(4350, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1505281878),
(4351, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1505281944),
(4352, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1505283850),
(4354, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1505291620),
(4355, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512649957),
(4356, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512650020),
(4357, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512650092),
(4358, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512650432),
(4359, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512952629),
(4360, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512964304),
(4361, 1, 'admin', '用户【qq】添加成功', '0.0.0.0', 1, 1512970208),
(4362, 21, 'qq', '用户【qq】登录成功', '0.0.0.0', 1, 1512970222),
(4363, 21, 'qq', '用户【qq】登录成功', '0.0.0.0', 1, 1512970238),
(4364, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512970259),
(4365, 21, 'qq', '用户【qq】登录成功', '0.0.0.0', 1, 1512970289),
(4366, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1512970406),
(4367, 1, 'admin', '用户【123】添加成功', '0.0.0.0', 1, 1512980116),
(4368, 22, '123', '用户【123】登录成功', '0.0.0.0', 1, 1512980159),
(4369, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513039212),
(4370, 1, 'admin', '用户【1】添加成功', '0.0.0.0', 1, 1513039791),
(4371, 1, 'admin', '用户【admin】删除管理员成功(ID=21)', '0.0.0.0', 1, 1513041797),
(4372, 1, 'admin', '用户【2】添加成功', '0.0.0.0', 1, 1513042197),
(4373, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513043803),
(4374, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513125773),
(4375, 1, 'admin', '用户【admin】删除管理员成功(ID=25)', '0.0.0.0', 1, 1513137253),
(4376, 1, 'admin', '用户【admin】删除管理员成功(ID=24)', '0.0.0.0', 1, 1513137299),
(4377, 1, 'admin', '用户【admin】删除管理员成功(ID=23)', '0.0.0.0', 1, 1513137354),
(4378, 1, 'admin', '用户【admin】删除管理员成功(ID=22)', '0.0.0.0', 1, 1513137445),
(4379, 1, 'admin', '用户【2】添加成功', '0.0.0.0', 1, 1513137501),
(4380, 1, 'admin', '用户【admin】删除管理员成功(ID=26)', '0.0.0.0', 1, 1513137549),
(4381, 1, 'admin', '用户【3】添加成功', '0.0.0.0', 1, 1513137606),
(4382, 1, 'admin', '用户【admin】删除管理员成功(ID=27)', '0.0.0.0', 1, 1513137613),
(4383, 1, 'admin', '用户【2】添加成功', '0.0.0.0', 1, 1513137656),
(4384, 1, 'admin', '用户【admin】删除管理员成功(ID=28)', '0.0.0.0', 1, 1513137662),
(4385, 1, 'admin', '用户【2】添加成功', '0.0.0.0', 1, 1513137814),
(4386, 1, 'admin', '用户【admin】删除管理员成功(ID=29)', '0.0.0.0', 1, 1513137831),
(4387, 1, 'admin', '用户【2】添加成功', '0.0.0.0', 1, 1513138016),
(4388, 1, 'admin', '用户【admin】删除管理员成功(ID=30)', '0.0.0.0', 1, 1513138024),
(4389, 1, 'admin', '用户【2】添加成功', '0.0.0.0', 1, 1513138067),
(4390, 1, 'admin', '用户【admin】删除管理员成功(ID=31)', '0.0.0.0', 1, 1513138075),
(4391, 1, 'admin', '用户【22】添加成功', '0.0.0.0', 1, 1513143575),
(4392, 1, 'admin', '用户【admin】删除管理员成功(ID=32)', '0.0.0.0', 1, 1513143836),
(4393, 1, 'admin', '用户【2】添加成功', '0.0.0.0', 1, 1513146513),
(4394, 33, '2', '用户【2】登录成功', '0.0.0.0', 1, 1513146610),
(4395, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513146711),
(4396, 33, '2', '用户【2】登录成功', '0.0.0.0', 1, 1513146998),
(4397, 13, 'test', '用户【test】登录失败：该账号被禁用', '0.0.0.0', 2, 1513147107),
(4398, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513147113),
(4399, 13, 'test', '用户【test】登录失败：密码错误', '0.0.0.0', 2, 1513147147),
(4400, 13, 'test', '用户【test】登录成功', '0.0.0.0', 1, 1513147153),
(4401, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513147212),
(4402, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1513147269),
(4403, 13, 'test', '用户【test】登录成功', '0.0.0.0', 1, 1513147293),
(4404, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513147335),
(4405, 13, 'test', '用户【test】登录成功', '0.0.0.0', 1, 1513147397),
(4406, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513147805),
(4407, 13, 'test', '用户【test】登录成功', '0.0.0.0', 1, 1513147824),
(4408, 33, '2', '用户【2】登录成功', '0.0.0.0', 1, 1513148559),
(4409, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513155383),
(4410, 33, '2', '用户【2】登录成功', '0.0.0.0', 1, 1513155631),
(4411, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513213095),
(4412, 1, 'admin', '用户【3】添加成功', '0.0.0.0', 1, 1513213694),
(4413, 34, '3', '用户【3】登录成功', '0.0.0.0', 1, 1513213715),
(4414, 34, '3', '用户【3】登录成功', '0.0.0.0', 1, 1513213728),
(4415, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513213835),
(4416, 1, 'admin', '用户【4】添加成功', '0.0.0.0', 1, 1513223383),
(4417, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513231633),
(4418, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513235178),
(4419, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513235190),
(4420, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513235359),
(4421, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513235372),
(4422, 1, 'admin', '用户【5】添加成功', '0.0.0.0', 1, 1513235963),
(4423, 1, 'admin', '用户【admin】删除管理员成功(ID=34)', '0.0.0.0', 1, 1513236686),
(4424, 1, 'admin', '用户【admin】删除管理员成功(ID=33)', '0.0.0.0', 1, 1513236689),
(4425, 1, 'admin', '用户【222】添加成功', '0.0.0.0', 1, 1513236754),
(4426, 1, 'admin', '用户【3】添加成功', '0.0.0.0', 1, 1513236813),
(4427, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513236830),
(4428, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513236862),
(4429, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513237007),
(4430, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513237110),
(4431, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513237119),
(4432, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513237124),
(4433, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513237141),
(4434, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513237178),
(4435, 1, 'admin', '用户【222】编辑成功', '0.0.0.0', 1, 1513237192),
(4436, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513237203),
(4437, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513237562),
(4438, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513237583),
(4439, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513237646),
(4440, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513238455),
(4441, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513238481),
(4442, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513238486),
(4443, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513238558),
(4444, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513239082),
(4445, 1, 'admin', '用户【222】编辑成功', '0.0.0.0', 1, 1513239086),
(4446, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513239090),
(4447, 1, 'admin', '用户【test】编辑成功', '0.0.0.0', 1, 1513239094),
(4448, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513239099),
(4449, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513239137),
(4450, 1, 'admin', '用户【6】添加成功', '0.0.0.0', 1, 1513239176),
(4451, 1, 'admin', '用户【6】编辑成功', '0.0.0.0', 1, 1513239187),
(4452, 1, 'admin', '用户【6】编辑成功', '0.0.0.0', 1, 1513239202),
(4453, 1, 'admin', '用户【234】添加成功', '0.0.0.0', 1, 1513239240),
(4454, 1, 'admin', '用户【234】编辑成功', '0.0.0.0', 1, 1513239287),
(4455, 1, 'admin', '用户【6】编辑成功', '0.0.0.0', 1, 1513239291),
(4456, 1, 'admin', '用户【234】编辑成功', '0.0.0.0', 1, 1513239471),
(4457, 1, 'admin', '用户【6】编辑成功', '0.0.0.0', 1, 1513239475),
(4458, 1, 'admin', '用户【sfd】添加成功', '0.0.0.0', 1, 1513239493),
(4459, 1, 'admin', '用户【sfd】编辑成功', '0.0.0.0', 1, 1513239503),
(4460, 1, 'admin', '用户【234】编辑成功', '0.0.0.0', 1, 1513239511),
(4461, 1, 'admin', '用户【6】编辑成功', '0.0.0.0', 1, 1513239516),
(4462, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513239521),
(4463, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513239525),
(4464, 1, 'admin', '用户【sfd】编辑成功', '0.0.0.0', 1, 1513239546),
(4465, 1, 'admin', '用户【234】编辑成功', '0.0.0.0', 1, 1513239551),
(4466, 1, 'admin', '用户【test】编辑成功', '0.0.0.0', 1, 1513239555),
(4467, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513239559),
(4468, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513239562),
(4469, 1, 'admin', '用户【qwe】添加成功', '0.0.0.0', 1, 1513239601),
(4470, 1, 'admin', '用户【sfd】编辑成功', '0.0.0.0', 1, 1513239629),
(4471, 1, 'admin', '用户【qwe】编辑成功', '0.0.0.0', 1, 1513239633),
(4472, 1, 'admin', '用户【qwe】编辑成功', '0.0.0.0', 1, 1513239650),
(4473, 1, 'admin', '用户【sfd】编辑成功', '0.0.0.0', 1, 1513239654),
(4474, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513239793),
(4475, 1, 'admin', '用户【5】编辑成功', '0.0.0.0', 1, 1513239797),
(4476, 1, 'admin', '用户【n】添加成功', '0.0.0.0', 1, 1513239818),
(4477, 1, 'admin', '用户【n】编辑成功', '0.0.0.0', 1, 1513239828),
(4478, 1, 'admin', '用户【s】添加成功', '0.0.0.0', 1, 1513239942),
(4479, 1, 'admin', '用户【s】编辑成功', '0.0.0.0', 1, 1513239948),
(4480, 1, 'admin', '用户【n】编辑成功', '0.0.0.0', 1, 1513239955),
(4481, 13, 'test', '用户【test】登录成功', '0.0.0.0', 1, 1513240040),
(4482, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513240109),
(4483, 1, 'admin', '用户【admin】登录失败：该账号被禁用', '0.0.0.0', 2, 1513240179),
(4484, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513240281),
(4485, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513240305),
(4486, 1, 'admin', '用户【3】编辑成功', '0.0.0.0', 1, 1513240324),
(4487, 1, 'admin', '用户【4】编辑成功', '0.0.0.0', 1, 1513240338),
(4488, 1, 'admin', '用户【admin】删除管理员成功(ID=37)', '0.0.0.0', 1, 1513240351),
(4489, 1, 'admin', '用户【admin】删除管理员成功(ID=36)', '0.0.0.0', 1, 1513240355),
(4490, 1, 'admin', '用户【admin】删除管理员成功(ID=44)', '0.0.0.0', 1, 1513240359),
(4491, 1, 'admin', '用户【admin】删除管理员成功(ID=42)', '0.0.0.0', 1, 1513240505),
(4492, 1, 'admin', '用户【sfd】编辑成功', '0.0.0.0', 1, 1513240513),
(4493, 1, 'admin', '用户【43534】添加成功', '0.0.0.0', 1, 1513240676),
(4494, 1, 'admin', '用户【43534】编辑成功', '0.0.0.0', 1, 1513240694),
(4495, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513299118),
(4496, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1513299208),
(4497, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513299224),
(4498, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1513317484),
(4499, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1513563196),
(4500, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514250724),
(4501, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514250944),
(4502, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251349),
(4503, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251439),
(4504, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251526),
(4505, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251565),
(4506, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514251581),
(4507, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251605),
(4508, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251655),
(4509, 1, 'admin', '用户【admin】删除菜单成功', '0.0.0.0', 1, 1514251679),
(4510, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251707),
(4511, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251730),
(4512, 1, 'admin', '用户【admin】删除菜单成功', '0.0.0.0', 1, 1514251736),
(4513, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251821),
(4514, 1, 'admin', '用户【admin】添加菜单成功', '0.0.0.0', 1, 1514251874),
(4515, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1514334564),
(4516, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1514473145),
(4517, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484093),
(4518, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484100),
(4519, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484112),
(4520, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484121),
(4521, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484154),
(4522, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484165),
(4523, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484176),
(4524, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484185),
(4525, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514484193),
(4526, 13, 'test', '用户【test】登录成功', '0.0.0.0', 1, 1514484564),
(4527, 13, 'test', '用户【asd】添加成功', '0.0.0.0', 1, 1514484930),
(4528, 13, 'test', '用户【dd】添加成功', '0.0.0.0', 1, 1514485001),
(4529, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1514485262),
(4530, 1, 'admin', '用户【admin】删除管理员成功(ID=47)', '0.0.0.0', 1, 1514485274),
(4531, 1, 'admin', '用户【admin】删除管理员成功(ID=46)', '0.0.0.0', 1, 1514485296),
(4532, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514485495),
(4533, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514485522),
(4534, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514485534),
(4535, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514485546),
(4536, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514485559),
(4537, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514485569),
(4538, 1, 'admin', '用户【admin】编辑菜单成功', '0.0.0.0', 1, 1514485578),
(4539, 1, 'admin', '用户【admin】编辑权限成功', '0.0.0.0', 1, 1514485985),
(4540, 1, 'admin', '用户【admin】编辑权限成功', '0.0.0.0', 1, 1514486002),
(4541, 1, 'admin', '用户【admin】编辑权限成功', '0.0.0.0', 1, 1514486009),
(4542, 1, 'admin', '用户【admin】编辑权限成功', '0.0.0.0', 1, 1514486017),
(4543, 1, 'admin', '用户【admin】编辑权限成功', '0.0.0.0', 1, 1514486025),
(4544, 1, 'admin', '用户【admin】编辑权限成功', '0.0.0.0', 1, 1514486046),
(4545, 1, 'admin', '用户【admin】编辑权限成功', '0.0.0.0', 1, 1514486054),
(4546, 1, 'admin', '用户【admin】添加权限成功', '0.0.0.0', 1, 1514486140),
(4547, 1, 'admin', '用户【test2】添加成功', '0.0.0.0', 1, 1514508411),
(4548, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1514862100),
(4549, 1, 'admin', '用户【admin】登录成功', '0.0.0.0', 1, 1514949830);

-- --------------------------------------------------------

--
-- 表的结构 `think_member`
--

CREATE TABLE IF NOT EXISTS `think_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(64) DEFAULT NULL COMMENT '邮件或者手机',
  `nickname` varchar(32) DEFAULT NULL COMMENT '昵称',
  `sex` int(10) DEFAULT NULL COMMENT '1男2女',
  `password` char(32) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `head_img` varchar(128) DEFAULT NULL COMMENT '头像',
  `integral` int(11) DEFAULT '0' COMMENT '积分',
  `money` int(11) DEFAULT '0' COMMENT '账户余额',
  `mobile` varchar(11) DEFAULT NULL COMMENT '认证的手机号码',
  `create_time` int(11) DEFAULT '0' COMMENT '注册时间',
  `update_time` int(11) DEFAULT NULL COMMENT '最后一次登录',
  `login_num` varchar(15) DEFAULT NULL COMMENT '登录次数',
  `status` tinyint(1) DEFAULT NULL COMMENT '1正常  0 禁用',
  `closed` tinyint(1) DEFAULT '0' COMMENT '0正常，1删除',
  `token` char(32) DEFAULT '0' COMMENT '令牌',
  `session_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=212066 ;

--
-- 转存表中的数据 `think_member`
--

INSERT INTO `think_member` (`id`, `account`, `nickname`, `sex`, `password`, `group_id`, `head_img`, `integral`, `money`, `mobile`, `create_time`, `update_time`, `login_num`, `status`, `closed`, `token`, `session_id`) VALUES
(2, '1217037610', 'XiMi丶momo', 2, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\ab9f9c492871857e1a6c5bc1c658ef7f.jpg', 300, 200, '18809321956', 1476779394, 1476779394, '0', 1, 1, '0', ''),
(1, '18809321929', '醉凡尘丶Wordly', 1, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\admin.jpg', 92960, 73, '18809321929', 1476762875, 1476762875, '0', 1, 0, '0', ''),
(3, '1217037610', '紫陌轩尘', 1, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\293c8cd05478b029a378ac4e5a880303.jpg', 400, 434, '49494', 1476676516, 1476676516, '0', 1, 1, '0', ''),
(4, '', 'fag', 1, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\8a69f4c962e26265fd9f12efbff65013.jpg', 24, 424, '242', 1476425833, 1476425833, '0', 0, 1, '0', ''),
(5, '18809321928', '空谷幽兰', 2, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\admin.jpg', 53, 3636, '3636', 1476676464, 1476676464, '0', 1, 0, '0', ''),
(6, '', '787367373', 1, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\ab9f9c492871857e1a6c5bc1c658ef7f.jpg', 414, 9, '73737373', 1476425750, 1476425750, '0', 0, 1, '0', ''),
(7, '18809321929', 'XMi丶呵呵', 2, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\293c8cd05478b029a378ac4e5a880303.jpg', 373373, 33, '73', 1476692255, 1476692255, '0', 0, 0, '0', ''),
(8, '1246470984', 'XY', 1, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\8a69f4c962e26265fd9f12efbff65013.jpg', 7383, 73737373, '7373', 1476692123, 1476692123, '0', 1, 1, '0', ''),
(9, '18793189097', '25773', 1, 'd41d8cd98f00b204e9800998ecf8427e', 1, '20161122\\admin.jpg', 7373737, 77, '7373733', 1476433452, 1476433452, '0', 1, 1, '0', ''),
(10, '1246470984', 'XiYu', 2, 'e10adc3949ba59abbe56e057f20f883e', 1, '20161122\\ab9f9c492871857e1a6c5bc1c658ef7f.jpg', 100, 100, '18793189091', 1476694831, 1476694831, '0', 1, 1, '0', ''),
(11, '', '烟勤话少脾气好', 0, '', 1, '20161122\\293c8cd05478b029a378ac4e5a880303.jpg', 0, 0, '', 1488030906, 0, '0', 0, 0, '0', ''),
(12, '1246470984', 'XiYu', 2, 'e10adc3949ba59abbe56e057f20f883e', 1, '20161122\\8a69f4c962e26265fd9f12efbff65013.jpg', 100, 100, '18793189091', 1488030906, 1476694831, '0', 1, 1, '0', ''),
(212065, '111', '111', 0, 'deb2a3420354e40d55a1b0cb3a947cd0', 121, '<!doctype html>\n<html>\n<head>\n    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\n    <title>跳转提示</title>\n', 0, 0, '', 1502341127, 1502341127, NULL, NULL, 0, '0', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `think_member_group`
--

CREATE TABLE IF NOT EXISTS `think_member_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '留言Id',
  `group_name` varchar(32) NOT NULL COMMENT '留言评论作者',
  `status` tinyint(1) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '留言回复时间',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章评论表' AUTO_INCREMENT=122 ;

--
-- 转存表中的数据 `think_member_group`
--

INSERT INTO `think_member_group` (`id`, `group_name`, `status`, `create_time`, `update_time`) VALUES
(1, '系统组', 0, 1441616559, 1502341098),
(2, '游客组', 1, 1441617195, 1502281865),
(3, 'VIP', 1, 1441769224, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `think_party`
--

CREATE TABLE IF NOT EXISTS `think_party` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `party` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `think_party`
--

INSERT INTO `think_party` (`id`, `name`, `party`) VALUES
(1, '甲方1', 1),
(2, '乙方1', 2),
(3, '甲方2', 1),
(4, '乙方2', 2),
(5, '安徽金寨抽水蓄能有限公司', 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_project`
--

CREATE TABLE IF NOT EXISTS `think_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '单元工程名称',
  `sn` varchar(20) NOT NULL COMMENT '单元工程编号',
  `post_sn` varchar(20) NOT NULL,
  `primary` varchar(10) NOT NULL COMMENT '是否为主要单元工程',
  `cate` varchar(20) NOT NULL COMMENT '质量账台类型',
  `kaigong_date` varchar(20) NOT NULL COMMENT '开工日期',
  `wangong_date` varchar(20) NOT NULL COMMENT '完工日期',
  `quantities` varchar(20) NOT NULL COMMENT '工程量',
  `pingding_date` varchar(20) NOT NULL COMMENT '评定日期',
  `zhuanghaoqi` varchar(20) NOT NULL COMMENT '桩号(起)',
  `zhuanghaozhi` varchar(20) NOT NULL COMMENT '桩号(止)',
  `gaochengqi` varchar(20) NOT NULL COMMENT '高程(起)',
  `gaochengzhi` varchar(20) NOT NULL COMMENT '高程(止)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `think_project`
--

INSERT INTO `think_project` (`id`, `pid`, `name`, `sn`, `post_sn`, `primary`, `cate`, `kaigong_date`, `wangong_date`, `quantities`, `pingding_date`, `zhuanghaoqi`, `zhuanghaozhi`, `gaochengqi`, `gaochengzhi`) VALUES
(4, 14, '1', 'QQ--WW-EE-', '1', '是', '开挖', '', '', '', '', '', '', '', ''),
(5, 14, '2', 'QQ--WW-EE-', '2', '是', '开挖', '', '', '', '', '', '', '', ''),
(6, 14, '3', 'QQ--WW-EE-', '3', '是', '支护', '', '', '', '', '', '', '', ''),
(8, 14, '4', 'QQ--WW-EE-', '4', '是', '支护', '', '', '', '', '', '', '', ''),
(9, 14, '5', 'QQ--WW-EE-', '5', '是', '混凝土', '', '', '', '', '', '', '', ''),
(10, 14, '6', 'QQ--WW-EE-', '6', '是', '混凝土', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `think_project_attachment`
--

CREATE TABLE IF NOT EXISTS `think_project_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_level_1` int(11) DEFAULT NULL,
  `id_level_2` int(11) DEFAULT NULL,
  `id_level_3` int(11) DEFAULT NULL,
  `id_level_4` int(11) DEFAULT NULL,
  `id_level_5` int(11) DEFAULT NULL,
  `filename` varchar(40) DEFAULT NULL,
  `owner` varchar(20) DEFAULT NULL,
  `department` varchar(40) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `think_project_attachment`
--

INSERT INTO `think_project_attachment` (`id`, `id_level_1`, `id_level_2`, `id_level_3`, `id_level_4`, `id_level_5`, `filename`, `owner`, `department`, `date`, `path`) VALUES
(10, 1, 0, 0, 0, 0, '功能列表V1.4.xlsx', 'admin', NULL, '2017-12-29 09:13:06', './uploads/attachment/20171229/fb0a0a3bbcba39911ce86cc8d77776b5.xlsx');

-- --------------------------------------------------------

--
-- 表的结构 `think_project_divide`
--

CREATE TABLE IF NOT EXISTS `think_project_divide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sn` varchar(20) NOT NULL COMMENT '单位/分部/单元工程编号',
  `post_sn` varchar(20) NOT NULL,
  `primary` varchar(20) NOT NULL COMMENT '是否为主要单位工程/分部工程/单元工程',
  `cate` varchar(20) NOT NULL COMMENT '质量台账类别',
  `accident` varchar(10) NOT NULL DEFAULT '否',
  `level` varchar(20) NOT NULL COMMENT '质量等级',
  `score_design` float NOT NULL,
  `score_actual` float NOT NULL,
  `score` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `think_project_divide`
--

INSERT INTO `think_project_divide` (`id`, `pid`, `name`, `sn`, `post_sn`, `primary`, `cate`, `accident`, `level`, `score_design`, `score_actual`, `score`) VALUES
(1, 0, '一级1', 'PP1', '', '是', '开挖', '否', '', 0, 0, 0),
(2, 0, '一级2', 'PP2', '', '是', '支护', '否', '', 0, 0, 0),
(12, 1, '二级', 'QQ', '', '是', '', '否', '', 0, 0, 0),
(13, 12, '三级', 'QQ-', 'WW', '是', '', '是', '优良', 0, 0, 0),
(14, 13, '四级', 'QQ--WW-', 'EE', '是', '开挖', '否', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_project_hunningtu`
--

CREATE TABLE IF NOT EXISTS `think_project_hunningtu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pass` varchar(20) NOT NULL COMMENT '一验合格',
  `quality_level` varchar(20) NOT NULL COMMENT '质量等级',
  `evaluated_date` varchar(20) NOT NULL COMMENT '评定月',
  `evaluated_by` varchar(20) NOT NULL COMMENT '评定人',
  `requirement_self` varchar(255) NOT NULL COMMENT '自检设计指标',
  `volume_self` float NOT NULL COMMENT '方量',
  `num_self` int(10) NOT NULL COMMENT '自检组数',
  `max_self` float NOT NULL COMMENT '最大值',
  `min_self` float NOT NULL COMMENT '最小值',
  `ave_self` float NOT NULL COMMENT '平均值',
  `pass_self` float NOT NULL COMMENT '合格率',
  `requirement_inspection` varchar(255) NOT NULL COMMENT '抽验设计指标',
  `volume_inspection` float NOT NULL,
  `num_inspection` int(10) NOT NULL,
  `max_inspection` float NOT NULL,
  `min_inspection` float NOT NULL,
  `ave_inspection` float NOT NULL,
  `pass_inspection` float NOT NULL,
  `points_flat` int(10) NOT NULL,
  `deviation_min_flat` varchar(255) NOT NULL,
  `deviation_max_flat` varchar(255) NOT NULL,
  `pass_flat` float NOT NULL,
  `points_vertical` int(10) NOT NULL,
  `deviation_min_vertical` varchar(255) NOT NULL,
  `deviation_max_vertical` varchar(255) NOT NULL,
  `pass_vertical` float NOT NULL,
  `allowable_deviation` varchar(255) NOT NULL COMMENT '允许偏差值（联合检测）',
  `points_union` int(10) NOT NULL,
  `max_union` float NOT NULL,
  `min_union` float NOT NULL,
  `ave_union` float NOT NULL,
  `pass_union` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `think_project_hunningtu`
--

INSERT INTO `think_project_hunningtu` (`id`, `uid`, `pass`, `quality_level`, `evaluated_date`, `evaluated_by`, `requirement_self`, `volume_self`, `num_self`, `max_self`, `min_self`, `ave_self`, `pass_self`, `requirement_inspection`, `volume_inspection`, `num_inspection`, `max_inspection`, `min_inspection`, `ave_inspection`, `pass_inspection`, `points_flat`, `deviation_min_flat`, `deviation_max_flat`, `pass_flat`, `points_vertical`, `deviation_min_vertical`, `deviation_max_vertical`, `pass_vertical`, `allowable_deviation`, `points_union`, `max_union`, `min_union`, `ave_union`, `pass_union`) VALUES
(1, 9, '是', '优良', '', '', '', 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, '', '', 0, 0, '', '', 0, '', 0, 0, 0, 0, 0),
(2, 10, '是', '优良', '', '', '', 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, '', '', 0, 0, '', '', 0, '', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_project_kaiwa`
--

CREATE TABLE IF NOT EXISTS `think_project_kaiwa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pass` varchar(20) NOT NULL COMMENT '一验合格',
  `quality_level` varchar(20) NOT NULL COMMENT '质量等级',
  `evaluated_date` varchar(20) NOT NULL COMMENT '评定月',
  `evaluated_by` varchar(20) NOT NULL COMMENT '评定人',
  `holes` int(10) NOT NULL COMMENT '检查孔数',
  `ave_size` float NOT NULL COMMENT '平均尺寸',
  `ave_length` float NOT NULL COMMENT '平均残孔长度',
  `half_percentage` float NOT NULL COMMENT '半孔率',
  `holes_test_date` varchar(20) NOT NULL COMMENT '检查时间',
  `sections` int(10) NOT NULL COMMENT '断面个数',
  `points` int(10) NOT NULL COMMENT '检查点数',
  `max` float NOT NULL COMMENT '最大值',
  `min` float NOT NULL COMMENT '最小值',
  `ave` float NOT NULL COMMENT '平均值',
  `sections_test_date` varchar(20) NOT NULL COMMENT '检查时间',
  `sections_overbreak` int(10) NOT NULL COMMENT '检查断面',
  `points_overbreak` int(10) NOT NULL COMMENT '检查点数',
  `max_overbreak` float NOT NULL COMMENT '最大超挖',
  `min_overbreak` float NOT NULL COMMENT '最小超挖',
  `ave_overbreak` float NOT NULL COMMENT '平均超挖',
  `pass_overbreak` float NOT NULL COMMENT '超挖合格率',
  `sections_underbreak` int(10) NOT NULL COMMENT '检查断面',
  `points_underbreak` int(10) NOT NULL COMMENT '检查点数',
  `max_underbreak` float NOT NULL COMMENT '最大欠挖',
  `min_underbreak` float NOT NULL COMMENT '最小欠挖',
  `ave_underbreak` float NOT NULL COMMENT '平均欠挖',
  `pass_underbreak` float NOT NULL COMMENT '欠挖合格率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `think_project_kaiwa`
--

INSERT INTO `think_project_kaiwa` (`id`, `uid`, `pass`, `quality_level`, `evaluated_date`, `evaluated_by`, `holes`, `ave_size`, `ave_length`, `half_percentage`, `holes_test_date`, `sections`, `points`, `max`, `min`, `ave`, `sections_test_date`, `sections_overbreak`, `points_overbreak`, `max_overbreak`, `min_overbreak`, `ave_overbreak`, `pass_overbreak`, `sections_underbreak`, `points_underbreak`, `max_underbreak`, `min_underbreak`, `ave_underbreak`, `pass_underbreak`) VALUES
(9, 5, '是', '优良', '', '阿什顿发放', 0, 0, 0, 0, '', 0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_project_zhihu`
--

CREATE TABLE IF NOT EXISTS `think_project_zhihu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `quality_level` varchar(20) NOT NULL,
  `evaluated_date` varchar(20) NOT NULL,
  `evaluated_by` varchar(20) NOT NULL,
  `area` float NOT NULL COMMENT '支护面积',
  `thickness` int(10) NOT NULL COMMENT '设计厚度',
  `points` int(10) NOT NULL COMMENT '检测点数',
  `max` float NOT NULL,
  `min` float NOT NULL,
  `ave` float NOT NULL,
  `quantity_self` int(10) NOT NULL COMMENT '施工数量',
  `num_self` int(10) NOT NULL COMMENT '自检根数',
  `ratio_self` float NOT NULL COMMENT '检测比例',
  `pass_self` int(10) NOT NULL COMMENT '合格根数',
  `anchor_max_self` float NOT NULL COMMENT '锚杆长度最大值',
  `anchor_min_self` float NOT NULL COMMENT '最小值',
  `casting_max_self` float NOT NULL COMMENT '注浆密实度最大值',
  `casting_min_self` float NOT NULL COMMENT '最小值',
  `pass_rate_self` float NOT NULL COMMENT '合格率',
  `quantity_inspection` int(10) NOT NULL,
  `num_inspection` int(10) NOT NULL,
  `ratio_inspection` float NOT NULL,
  `pass_inspection` int(10) NOT NULL,
  `anchor_max_inspection` float NOT NULL,
  `anchor_min_inspection` float NOT NULL,
  `casting_max_inspection` float NOT NULL,
  `casting_min_inspection` float NOT NULL,
  `pass_rate_inspection` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `think_project_zhihu`
--

INSERT INTO `think_project_zhihu` (`id`, `uid`, `pass`, `quality_level`, `evaluated_date`, `evaluated_by`, `area`, `thickness`, `points`, `max`, `min`, `ave`, `quantity_self`, `num_self`, `ratio_self`, `pass_self`, `anchor_max_self`, `anchor_min_self`, `casting_max_self`, `casting_min_self`, `pass_rate_self`, `quantity_inspection`, `num_inspection`, `ratio_inspection`, `pass_inspection`, `anchor_max_inspection`, `anchor_min_inspection`, `casting_max_inspection`, `casting_min_inspection`, `pass_rate_inspection`) VALUES
(1, 6, '是', '优良', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 8, '是', '优良', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_project_zhihu_maogan`
--

CREATE TABLE IF NOT EXISTS `think_project_zhihu_maogan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `standard` varchar(255) NOT NULL COMMENT '规格',
  `quantity_self` int(10) NOT NULL COMMENT '施工数量',
  `num_self` int(10) NOT NULL COMMENT '自检根数',
  `ratio_self` float NOT NULL COMMENT '检测比例',
  `pass_self` int(10) NOT NULL COMMENT '合格根数',
  `anchor_max_self` float NOT NULL COMMENT '锚杆长度最大值',
  `anchor_min_self` float NOT NULL COMMENT '最小值',
  `casting_max_self` float NOT NULL COMMENT '注浆密实度最大值',
  `casting_min_self` float NOT NULL COMMENT '最小值',
  `pass_rate_self` float NOT NULL COMMENT '合格率',
  `quantity_inspection` int(10) NOT NULL,
  `num_inspection` int(10) NOT NULL,
  `ratio_inspection` float NOT NULL,
  `pass_inspection` int(10) NOT NULL,
  `anchor_max_inspection` float NOT NULL,
  `anchor_min_inspection` float NOT NULL,
  `casting_max_inspection` float NOT NULL,
  `casting_min_inspection` float NOT NULL,
  `pass_rate_inspection` float NOT NULL,
  `pull_self` int(10) NOT NULL COMMENT '设计拉拔力值',
  `test_num_self` int(10) NOT NULL COMMENT '施工单位自检根数',
  `pass_num_self` int(10) NOT NULL COMMENT '合格根数',
  `pull_max_self` float NOT NULL COMMENT '最大值',
  `pull_min_self` float NOT NULL COMMENT '最小值',
  `pull_ave_self` float NOT NULL COMMENT '平均值',
  `pull_pass_self` float NOT NULL COMMENT '自检合格率',
  `pull_inspection` int(10) NOT NULL COMMENT '合格率',
  `test_num_inspection` int(10) NOT NULL,
  `pass_num_inspection` int(10) NOT NULL,
  `pull_max_inspection` float NOT NULL,
  `pull_min_inspection` float NOT NULL,
  `pull_ave_inspection` float NOT NULL,
  `pull_pass_inspection` float NOT NULL,
  `level_self` varchar(20) NOT NULL COMMENT '设计等级',
  `mortar_num_self` int(10) NOT NULL,
  `mortar_max_self` float NOT NULL,
  `mortar_min_self` float NOT NULL,
  `mortar_ave_self` float NOT NULL,
  `mortar_pass_self` float NOT NULL,
  `level_inspection` varchar(20) NOT NULL,
  `mortar_num_inspection` int(10) NOT NULL,
  `mortar_max_inspection` float NOT NULL,
  `mortar_min_inspection` float NOT NULL,
  `mortar_ave_inspection` float NOT NULL,
  `mortar_pass_inspection` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_qc_attachment`
--

CREATE TABLE IF NOT EXISTS `think_qc_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `phase_id` int(11) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `owner` varchar(20) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `revision` varchar(20) DEFAULT NULL COMMENT '版本',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_qc_group`
--

CREATE TABLE IF NOT EXISTS `think_qc_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(40) NOT NULL,
  `found_date` varchar(20) NOT NULL COMMENT '成立时间',
  `register_date` varchar(20) NOT NULL COMMENT '注册时间',
  `topic_name` varchar(40) NOT NULL COMMENT '课题名称',
  `topic_type` varchar(20) NOT NULL COMMENT '课题类型',
  `implement_date` varchar(20) NOT NULL COMMENT '实施日期',
  `status` varchar(20) NOT NULL,
  `group_leader` varchar(20) NOT NULL COMMENT '组长',
  `activity_start_date` varchar(20) NOT NULL COMMENT '活动时间',
  `activity_times_part1` varchar(255) NOT NULL COMMENT '活动次数',
  `activity_times_part2` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `goal` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_qc_member`
--

CREATE TABLE IF NOT EXISTS `think_qc_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `education` varchar(40) NOT NULL,
  `age` int(10) NOT NULL,
  `title` varchar(20) NOT NULL COMMENT '技术职称',
  `division` text NOT NULL COMMENT 'qc小组分工',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_qc_problem`
--

CREATE TABLE IF NOT EXISTS `think_qc_problem` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `problem` varchar(255) NOT NULL COMMENT '末端因素',
  `standard` varchar(80) NOT NULL COMMENT '确认标准',
  `method` varchar(80) NOT NULL COMMENT '确认方法',
  `date` varchar(20) NOT NULL COMMENT '确认时间',
  `owner` varchar(20) NOT NULL COMMENT '确认人',
  `description` varchar(255) NOT NULL COMMENT '确认情况',
  `result` varchar(40) NOT NULL COMMENT '论证结果'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `think_qc_strategy`
--

CREATE TABLE IF NOT EXISTS `think_qc_strategy` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `problem` varchar(255) NOT NULL COMMENT '要因',
  `strategy` varchar(255) NOT NULL COMMENT '对策',
  `goal` varchar(255) NOT NULL COMMENT '目标',
  `method` varchar(255) NOT NULL COMMENT '措施',
  `date` varchar(20) NOT NULL COMMENT '执行时间',
  `owner` varchar(20) NOT NULL COMMENT '负责人'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `think_second_party`
--

CREATE TABLE IF NOT EXISTS `think_second_party` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `think_second_party`
--

INSERT INTO `think_second_party` (`id`, `name`) VALUES
(1, 'test3'),
(2, 'test4'),
(3, '中国水利水电建设工程咨询北京有限公司'),
(4, '中国葛洲坝集团股份有限公司'),
(5, '中国水利水电第六工程局有限公司');

-- --------------------------------------------------------

--
-- 表的结构 `think_user`
--

CREATE TABLE IF NOT EXISTS `think_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(20) DEFAULT NULL COMMENT '认证的手机号码',
  `nickname` varchar(32) DEFAULT NULL COMMENT '昵称',
  `password` char(32) DEFAULT NULL,
  `head_img` varchar(255) DEFAULT NULL COMMENT '头像',
  `status` tinyint(1) DEFAULT NULL COMMENT '1激活  0 未激活',
  `token` varchar(255) DEFAULT '0' COMMENT '令牌',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `think_user`
--

INSERT INTO `think_user` (`id`, `account`, `nickname`, `password`, `head_img`, `status`, `token`) VALUES
(1, '18693281982', '田建龙', 'e10adc3949ba59abbe56e057f20f883e', 'http://123.56.237.22:8888/group1/M00/00/08/ezjtFlj4IHyAcjlzAABDms0T3Kk671.jpg', 1, 'LWBYIiLWinNiulNXYD1UzGgfynNx+gy/zmq5Ega0E0we4a0WyB8UaG4x+VKRoc9CG4e1BXrqZww='),
(2, '18993075721', '账号1', 'e10adc3949ba59abbe56e057f20f883e', 'http://opgkfon0o.bkt.clouddn.com/108.png', 1, 'VslU7gKYuddZFPq4ssWLZCNYBsi3YQIicyG1jm5pUfvZHI4qw03b3A2sygA4efLyWHRkYBQX8LAscwsA7sLzhg=='),
(3, '15095340657', '呼丽华', 'e10adc3949ba59abbe56e057f20f883e', 'http://123.56.237.22:8888/group1/M00/00/00/ezjtFliGwvWAaYeXAABu1D1rZNo655.jpg', 1, '2d8471d156a9e6db155145571cedea5a');

-- --------------------------------------------------------

--
-- 表的结构 `think_video`
--

CREATE TABLE IF NOT EXISTS `think_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `date` varchar(20) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `think_video`
--

INSERT INTO `think_video` (`id`, `name`, `date`, `path`) VALUES
(5, '请求', '2017-12-29 15:22:32', './uploads/video/20171229/d7209bfd7ff3f4330e600c0ded4cc623.mp4');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
