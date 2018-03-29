<?php namespace XoopsModules\Latestnews;

use Xmf\Request;
use XoopsModules\Latestnews;
use XoopsModules\Latestnews\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------
}
