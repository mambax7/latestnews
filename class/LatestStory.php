<?php

namespace XoopsModules\Latestnews;

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

use XoopsModules\Latestnews;
use XoopsModules\News;

/**
 * Class LatestStory
 * @package XoopsModules\Latestnews
 */
class LatestStory extends News\NewsStory
{
    /**
     * LatestStory constructor.
     * @param int $id
     */
    public function __construct($id = -1)
    {
        parent::__construct($id);
    }

    /**
     * Returns published stories according to some options
     * @param int    $limit
     * @param bool   $selected_stories
     * @param int    $start
     * @param bool   $checkRight
     * @param int    $topic
     * @param int    $ihome
     * @param bool   $asobject
     * @param string $order
     * @param bool   $topic_frontpage
     * @return array
     */
    public static function getAllPublished(
        $limit = 0,
        $selected_stories = true,
        $start = 0,
        $checkRight = false,
        $topic = 0,
        $ihome = 0,
        $asobject = true,
        $order = 'published',
        $topic_frontpage = false)
    {
        $db   = \XoopsDatabaseFactory::getDatabaseConnection();
        $myts = \MyTextSanitizer::getInstance();

        $ret = [];
        $sql = 'SELECT s.*, t.* FROM ' . $db->prefix('news_stories') . ' s, ' . $db->prefix('news_topics') . ' t WHERE (s.published > 0 AND s.published <= ' . time() . ') AND (s.expired = 0 OR s.expired > ' . time() . ') AND (s.topicid=t.topic_id) ';
        if (0 != $topic) {
            if ($selected_stories) {
                $sql .= ' AND s.storyid IN (' . $selected_stories . ')';
            }

            if (!is_array($topic)) {
                if ($checkRight) {
                    $topics = News\Utility::getMyItemIds('news_view');
                    if (!in_array($topic, $topics)) {
                        return null;
                    }
                    $sql .= ' AND s.topicid=' . (int)$topic . ' AND (s.ihome=1 OR s.ihome=0)';
                } else {
                    $sql .= ' AND s.topicid=' . (int)$topic . ' AND (s.ihome=1 OR s.ihome=0)';
                }
            } else {
                if ($checkRight) {
                    $topics = News\Utility::getMyItemIds('news_view');
                    $topic  = array_intersect($topic, $topics);
                }
                if (count($topic) > 0) {
                    $sql .= ' AND s.topicid IN (' . implode(',', $topic) . ')';
                } else {
                    return null;
                }
            }
        } else {
            if ($checkRight) {
                $topics = News\Utility::getMyItemIds('news_view');
                if (count($topics) > 0) {
                    $topics = implode(',', $topics);
                    $sql    .= ' AND s.topicid IN (' . $topics . ')';
                } else {
                    return null;
                }
            }
            if (0 == (int)$ihome) {
                $sql .= ' AND s.ihome=0';
            }
        }
        if ($topic_frontpage) {
            $sql .= ' AND t.topic_frontpage=1';
        }
        $sql    .= " ORDER BY s.$order DESC";
        $result = $db->query($sql, (int)$limit, (int)$start);

        while (false !== ($myrow = $db->fetchArray($result))) {
            if ($asobject) {
                $ret[] = new self($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Function used to prepare an article to be showned
     * @param $filescount
     * @return array
     */
    public function prepare2show($filescount)
    {
        require_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
        global $xoopsUser, $xoopsConfig;
        /** @var Latestnews\Helper $helper */
        $helper = Latestnews\Helper::getInstance();

        $myts                 = \MyTextSanitizer::getInstance();
        $infotips             = News\Utility::getModuleOption('infotips');
        $story                = [];
        $story['id']          = $this->storyid();
        $story['poster']      = $this->uname();
        $story['author_name'] = $this->uname();
        $story['author_uid']  = $this->uid();
        if (false !== $story['poster']) {
            $story['poster'] = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $this->uid() . "'>" . $story['poster'] . '</a>';
        } else {
            if (3 != $helper->getConfig('displayname')) {
                $story['poster'] = $xoopsConfig['anonymous'];
            }
        }
        if ('' !== $helper->getConfig('ratenews')) {
            $story['rating'] = number_format($this->rating(), 2);
            if (1 == $this->votes) {
                $story['votes'] = _NW_ONEVOTE;
            } else {
                $story['votes'] = sprintf(_NW_NUMVOTES, $this->votes);
            }
        }
        $story['posttimestamp']     = $this->published();
        $story['posttime']          = formatTimestamp($story['posttimestamp'], News\Utility::getModuleOption('dateformat'));
        $story['topic_description'] = $myts->displayTarea($this->topic_description);

        $auto_summary = '';
        $tmp          = '';
        $auto_summary = $this->auto_summary($this->bodytext(), $tmp);

        $story['text'] = $this->hometext();
        $story['text'] = str_replace('[summary]', $auto_summary, $story['text']);

        $introcount = mb_strlen($story['text']);
        $fullcount  = mb_strlen($this->bodytext());
        $totalcount = $introcount + $fullcount;

        $morelink = '';
        if ($fullcount > 1) {
            $morelink .= '<a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . '';
            $morelink .= '">' . _NW_READMORE . '</a>';
            $morelink .= ' | ' . sprintf(_NW_BYTESMORE, $totalcount);
            if ('' !== $helper->getConfig('com_rule') && XOOPS_COMMENT_APPROVENONE != $helper->getConfig('com_rule')) {
                $morelink .= ' | ';
            }
        }
        if ('' !== $helper->getConfig('com_rule') && XOOPS_COMMENT_APPROVENONE != $helper->getConfig('com_rule')) {
            $ccount    = $this->comments();
            $morelink  .= '<a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . '';
            $morelink2 = '<a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . '';
            if (0 == $ccount) {
                $morelink .= '">' . _NW_COMMENTS . '</a>';
            } else {
                if ($fullcount < 1) {
                    if (1 == $ccount) {
                        $morelink .= '">' . _NW_READMORE . '</a> | ' . $morelink2 . '">' . _NW_ONECOMMENT . '</a>';
                    } else {
                        $morelink .= '">' . _NW_READMORE . '</a> | ' . $morelink2 . '">';
                        $morelink .= sprintf(_NW_NUMCOMMENTS, $ccount);
                        $morelink .= '</a>';
                    }
                } else {
                    if (1 == $ccount) {
                        $morelink .= '">' . _NW_ONECOMMENT . '</a>';
                    } else {
                        $morelink .= '">';
                        $morelink .= sprintf(_NW_NUMCOMMENTS, $ccount);
                        $morelink .= '</a>';
                    }
                }
            }
        }
        $story['morelink']  = $morelink;
        $story['adminlink'] = '';

        $approveprivilege = 0;
        if ($this->latest_news_is_admin_group()) {
            $approveprivilege = 1;
        }

        if ('' !== $helper->getConfig('authoredit') && 1 == $helper->getConfig('authoredit')
            && (is_object($xoopsUser)
                && $xoopsUser->getVar('uid') == $this->uid())) {
            $approveprivilege = 1;
        }
        if ($approveprivilege) {
            $story['adminlink'] = $this->adminlink();
        }
        $story['mail_link'] = 'mailto:?subject=' . sprintf(_NW_INTARTICLE, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_NW_INTARTFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid();
        $story['imglink']   = '';
        $story['align']     = '';
        if ($this->topicdisplay()) {
            $story['imglink'] = $this->imglink();
            $story['align']   = $this->topicalign();
        }
        if ($infotips > 0) {
            $story['infotips'] = ' title="' . news_make_infotips($this->hometext()) . '"';
        } else {
            $story['infotips'] = '';
        }
        $story['title'] = "<a href='" . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . "'" . $story['infotips'] . '>' . $this->title() . '</a>';

        $story['hits'] = $this->counter();
        if ($filescount > 0) {
            $story['files_attached'] = true;
            $story['attached_link']  = "<a href='" . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . "' title='" . _NW_ATTACHEDLIB . "'><img src='" . XOOPS_URL . '/modules/news/assets/images/attach.gif' . "' title='" . _NW_ATTACHEDLIB . "'></a>";
        } else {
            $story['files_attached'] = false;
            $story['attached_link']  = '';
        }

        return $story;
    }

    /**
     * @return bool
     */
    public function latest_news_is_admin_group()
    {
        global $xoopsUser;
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $xoopsModule   = $moduleHandler->getByDirname('news');
        if (is_object($xoopsUser)) {
            if (in_array('1', $xoopsUser->getGroups())) {
                return true;
            }
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                return true;
            }

            return false;
        }

        return false;
    }
}
