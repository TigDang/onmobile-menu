<?php
/* Smarty version 3.1.33, created on 2022-04-25 14:58:32
  from 'D:\Vertrigo\www\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6266b7187f23d1_58857209',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bf669d78ce9a4c3f6d2329dfc1d7bbecab77ef0a' => 
    array (
      0 => 'D:\\Vertrigo\\www\\templates\\index.tpl',
      1 => 1551174138,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6266b7187f23d1_58857209 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
<head>	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="VertrigoServ WAMP Server" />
<meta name="robots" content="index,follow,all" />
<meta name="description" content="VertrigoServ - WAMP PHP " />
<meta name="keywords" content="Apache, MySQL, PHP, PHP5, Phpmyadmin, Zend Optimizer, Professional, Windows, Server, Secure, HTTP, WAMP, XHTML, CSS, Installation, Simple, Freeware" />
<title>VertrigoServ</title>
<link rel="stylesheet" type="text/css" href="inc/style.css" title="Main" media="screen" />
</head>
<body>
<div>
<div class="center"><h1><?php echo $_smarty_tpl->tpl_vars['lang']->value['top'];?>
</h1></div>
<div class="right white" id="lang"><?php echo $_smarty_tpl->tpl_vars['lang']->value['change_lang'];?>
<a href="?lang=pl"><img src="gfx/pl.gif" alt="PL" /></a><a href="?lang=eng"><img src="gfx/eng.gif" alt="ENG" /></a></div>
<br class="clear" />
<div class="left box">
<div class="box_top"></div>
<div class="right box_img"><img src="gfx/info.png" alt=""  /></div>
<div class="box_header"><?php echo $_smarty_tpl->tpl_vars['lang']->value['info'];?>
</div>
<div class="box_content">
<br/> 
<p><?php echo $_smarty_tpl->tpl_vars['lang']->value['left_1'];?>
</p>
<p><b><?php echo $_smarty_tpl->tpl_vars['lang']->value['packages'];?>
</b></p>
 <ul>
            <li>Apache <span class="red">2.4.38</span></li>
            <li>PHP <span class="red"><?php echo $_smarty_tpl->tpl_vars['php_version']->value;?>
</span></li>
            <li>MySQL <span class="red">5.7.25</span></li>
			<li>Smarty <span class="red"><?php echo Smarty::SMARTY_VERSION;?>
</span></li>		
            <li>SQLite <span class="red">3.27.2</span></li>			
            <li>PhpMyAdmin <span class="red">4.8.5</span></li>
            <li>Xdebug <span class="red">2.6.1 / 2.5.5</span></li>
</ul>
<p><b><?php echo $_smarty_tpl->tpl_vars['lang']->value['php_ext'];?>
</b><br/><br/><?php echo $_smarty_tpl->tpl_vars['extensions']->value;?>
</p>
<p><b><?php echo $_smarty_tpl->tpl_vars['lang']->value['mysql_ps'];?>
</b></p>
<?php if ($_smarty_tpl->tpl_vars['password_status']->value) {?>
<div id="mysql_green"><?php echo $_smarty_tpl->tpl_vars['lang']->value['mysql_green'];?>
</div>
<?php } else { ?>
<div id="mysql_red"><?php echo $_smarty_tpl->tpl_vars['lang']->value['mysql_red'];?>
</div>
<?php }?>
</div>
</div>
<div class="right box">
<div class="box_top"></div>
<div class="right box_img"><img src="/gfx/tools.jpg" alt=""  /></div>
<div class="box_header"><?php echo $_smarty_tpl->tpl_vars['lang']->value['tools'];?>
</div>
<div class="box_content">
<div class="center">
<br/>
<p>
<!-- <?php echo $_smarty_tpl->tpl_vars['lang']->value['local_tools'];?>
  --> 
            <a class="btn btn_t" href="/phpmyadmin">PhpMyAdmin</a>
            <a class="btn btn_c" href="inc/phpinfo.php"><?php echo $_smarty_tpl->tpl_vars['lang']->value['view_phpinfo'];?>
</a>
            <a class="btn btn_c" href="inc/extensions.php"><?php echo $_smarty_tpl->tpl_vars['lang']->value['view_extensions'];?>
</a>
			<a class="btn btn_b" href="server-info"><?php echo $_smarty_tpl->tpl_vars['lang']->value['apache_info'];?>
</a>
<!-- <?php echo $_smarty_tpl->tpl_vars['lang']->value['links'];?>
  --> <br/>
            <a class="btn btn_t" href="http://www.vswamp.com"><?php echo $_smarty_tpl->tpl_vars['lang']->value['homepage'];?>
</a>
			<a class="btn btn_c" href="http://www.recentversions.com">Recent versions of software</a>
			<a class="btn btn_b" href="http://sourceforge.net/donate/index.php?group_id=113444">VertrigoServ Donate</a>
<!-- <?php echo $_smarty_tpl->tpl_vars['lang']->value['manuals'];?>
 --> <br/>
			<a class="btn btn_t" href="http://httpd.apache.org/docs/2.4/">Apache 2.4 documentation</a>
			<a class="btn btn_c" href="http://www.php.net/docs.php">PHP Manual</a>
			<a class="btn btn_c" href="http://dev.mysql.com/doc/">MySQL Manual</a>
			<a class="btn btn_c" href="http://www.phpmyadmin.net/home_page/docs.php">phpMyAdmin Manual</a>
			<a class="btn btn_c" href="http://www.smarty.net/docs.php">Smarty Manual</a>
			<a class="btn btn_b" href="http://www.sqlite.org/docs.html">SQLite Manual</a>
</p>
</div>
</div>
</div>
<div class="right" id="valid"><img src="gfx/xhtml.gif" alt="Valid XHTML" /><img src="gfx/css.gif" alt="Valid CSS" /></div>
<br class="clear"/>
<br/>
<div class="center white" id="footer">Copyright &copy; 2004-2018 by <a href="mailto:handzlik(at)gmail.com">VertrigoServ WAMP Server</a></div>
<br/>
</div>
</body>
</html><?php }
}
