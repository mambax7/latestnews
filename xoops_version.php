<?php
// ######################################################################
// #                                                                    #
// # Latest News block by Mowaffak ( www.arabxoops.com )                #
// # based on Last Articles Block by Pete Glanz (www.glanz.ru)          #
// # Thanks to:                                                         #
// # Trabis ( www.xuups.com ) and Bandit-x ( www.bandit-x.net )         #
// #                                                                    #
// ######################################################################
// # Use of this program is goverened by the terms of the GNU General   #
// # Public License (GPL - version 1 or 2) as published by the          #
// # Free Software Foundation (http://www.gnu.org/)                     #
// ######################################################################
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

include __DIR__ . '/preloads/autoloader.php';

$modversion['version']       = '0.73';
$modversion['module_status'] = 'Beta 2';
$modversion['release_date']  = '2017/07/20';
$modversion['name']          = _MI_LATESTNEWS_BLOCK;
$modversion['description']   = _MI_LATESTNEWS_BLOCKS_DESC;
$modversion['credits']       = 'Original version Darren Poulton<br>paladin@intaleather.com.au<br>http://paladin.intaleather.com.au/<br>Ported by: Sylvain B. (sylvain@123rando.com)<br>Altered version SUIN<br>http://tms.s10.xrea.com:8080/';
$modversion['author']        = 'Mowaffak, Trabis, Bandit-x';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU GPL 2.0 or later';
$modversion['license_url']   = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']      = 0; //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
$modversion['image']         = 'assets/images/logoModule.png';
$modversion['dirname']       = basename(__DIR__);
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';

//about
$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
//$modversion['adminindex'] = "admin/myblocksadmin.php";
$modversion['adminmenu'] = 'admin/menu.php';

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_LATESTNEWS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_LATESTNEWS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_LATESTNEWS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_LATESTNEWS_SUPPORT, 'link' => 'page=support'],
];

// Blocks
$modversion['blocks'][1]['file']      = 'latestnews.php';
$modversion['blocks'][1]['name']      = _MI_LATESTNEWS_BLOCK;
$modversion['blocks'][1]['show_func'] = 'block_latestnews_show';
$modversion['blocks'][1]['edit_func'] = 'b_latestnews_edit';
$modversion['blocks'][1]['template']  = 'block_latestnews.tpl';
$modversion['blocks'][1]['options']   = '6|2|200|100|100|2|dcdcdc|0|right|1|1|1|1|1|1|1|1|1|1|1|1|0|100|30|published|';
$modversion['blocks'][1]['can_clone'] = true;

// On Update
if (!empty($_POST['fct']) && !empty($_POST['op']) && !empty($_POST['diranme']) && 'modulesadmin' === $_POST['fct']
    && 'update_ok' === $_POST['op']
    && $_POST['dirname'] == $modversion['dirname']) {
    include __DIR__ . '/include/onupdate.inc.php';
}
