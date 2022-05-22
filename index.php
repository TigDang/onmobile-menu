<?php

$new_url = 'pages/main_menu.php';
header('Location: '.$new_url);


$smarty = new Smarty;
$current_lang = set_lang();
$smarty->assign('lang',$lang[$current_lang]);
$extensions = '';
$loaded_ext = get_loaded_extensions(); foreach ($loaded_ext as $ext) $extensions.=$ext.', ';
$smarty->assign('extensions',$extensions);

if (((extension_loaded('mysqli')))&&(@mysqli_connect('localhost','root','vertrigo'))) 
 $smarty->assign('password_status', false); 
else 
 $smarty->assign('password_status', true);

$smarty->assign('php_version', phpversion());
 
$smarty->display('index.tpl');

?>