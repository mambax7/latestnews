<?php

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Xmf\Module\Helper::getHelper('system');
}


$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
//$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

$moduleHelper->loadLanguage('modinfo');

$adminmenu              = [];
$i                      = 0;
'title' =>  _AM_MODULEADMIN_HOME,
'link' =>  'admin/index.php',
'icon' =>  $pathIcon32 . '/home.png',
++$i;
'title' =>  _MI_LATESTNEWS_ADMIN_MENU1,
'link' =>  'admin/blocksadmin.php',
'icon' =>  $pathIcon32 . '/block.png',
++$i;
'title' =>  _MI_LATESTNEWS_ADMIN_MENU2,
'link' =>  'admin/permissions.php',
'icon' =>  $pathIcon32 . '/permissions.png',
++$i;
'title' =>  _AM_MODULEADMIN_ABOUT,
'link' =>  'admin/about.php',
'icon' =>  $pathIcon32 . '/about.png',
