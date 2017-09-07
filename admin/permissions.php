<?php
/**
 * Module: LatestNews
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * PHP version 5
 *
 * @category        Module
 * @package         LatestNews
 * @author          XOOPS Development Team
 * @copyright       2001-2016 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @since           0.73
 */

require_once __DIR__ . '/admin_header.php';

xoops_cp_header();

$permissions_admin = \Xmf\Module\Admin::getInstance();
echo $permissions_admin->displayNavigation(basename(__FILE__));

$moduleId = $xoopsModule->getVar('mid');

$formTitle             = _AM_LATESTNEWS_PERMISSIONS;
$permissionName        = basename(dirname(__DIR__));
$permissionDescription = _AM_LATESTNEWS_PERMISSIONS_DSC;
$global_perms_array    = [
    '1' => _AM_LATESTNEWS_ACTIVERIGHTS,
    '2' => _AM_LATESTNEWS_ACCESSRIGHTS
];

$permissionsForm = new XoopsGroupPermForm($formTitle, $moduleId, $permissionName, $permissionDescription, 'admin/permissions.php');
foreach ($global_perms_array as $perm_id => $permissionName) {
    $permissionsForm->addItem($perm_id, $permissionName);
}
echo $permissionsForm->render();
echo "<br><br><br><br>\n";
unset($permissionsForm);

require_once __DIR__ . '/admin_footer.php';
