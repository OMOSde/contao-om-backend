<?php

/**
 * Contao bundle contao-om-backend
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      https://github.com/OMOSde/contao-om-backend
 * @license   LGPL 3.0+
 */


/**
 * Namespace
 */
namespace OMOSde\ContaoOmBackendBundle;


/**
 * Class BackendLinks
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class BackendLinks extends \Backend
{
    /**
     * Hooks constructor.
     */
    public function __construct()
    {
        $this->import('BackendUser', 'User');
    }


    /**
     * Add a backend contact link in top navigation
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed
     */
    public function addBackendLinksTop($strContent, $strTemplate)
    {
        if ($strTemplate != 'be_main')
        {
            return $strContent;
        }

        $objLinks = OmBackendLinksTopModel::findByPublished(1);
        if (!$objLinks)
        {
            return $strContent;
        }

        $strLinks = '';
        foreach ($objLinks as $link)
        {
            $strStyle = ($link->icon) ? sprintf('background:url(%s) left 17px no-repeat;', \FilesModel::findByUuid($link->icon)->path) : sprintf('display:inline-block;margin-left:18px;padding: 17px 10px 17px 10px;');
            $strLink  = ($link->url) ? sprintf('<li><a href="%s" style="%s">%s</a></li>', $link->url, $strStyle, $link->title) : sprintf('<li><span style="%s">%s</span></li>', $strStyle, $link->title);

            $strLinks .= $strLink;
        }

        $strContent = str_replace('<ul id="tmenu">', '<ul id="tmenu">'.$strLinks, $strContent);

        return $strContent;
    }


    /**
     * Generate backend links
     *
     * @return string;
     */
    public function addBackendLinksMain($strContent, $strTemplate)
    {
        if ($strTemplate != 'be_main')
        {
            return $strContent;
        }

        // declare variables
        $arrGroups = null;
        $strReturn = '';

        // get all links
        //$objLinks = $this->Database->prepare("SELECT * FROM tl_om_backend_links WHERE language=? AND published=1")->execute($this->User->language);

        $objLinks = OmBackendLinksMainModel::findBy(array('language=?', 'published=1'), array($this->User->language));
        if (!$objLinks)
        {
            return $strReturn;
        }

        foreach ($objLinks as $link)
        {
            $arrGroups[$link->be_group][$link->title] = $link->url;
        }

        foreach ($arrGroups as $groupName=>$group)
        {
            $strReturn .= '<li class="tl_level_1_group"><a href="contao/main.php?do=repository_manager&amp;mtg='.$groupName.'" title="" onclick="return AjaxRequest.toggleNavigation(this,\''.$groupName.'\')"><img src="system/themes/default/images/modMinus.gif" width="16" height="16" alt="">'.$groupName.'</a></li>';
            $strReturn .= '<li class="tl_parent" id="'.$groupName.'" style="display: inline;"><ul class="tl_level_2">';
            foreach ($group as $linkTitle=>$link)
            {
                if (strpos($link, 'contao/main.php') !== false)
                {
                    $link .= (strpos($link, '?') !== false) ? '&' : '?';
                    $strReturn .= '<li><a href="'.$link.'rt=' . $_SESSION['REQUEST_TOKEN'] . '" class="navigation themes" title="">'.$linkTitle.'</a></li>';
                } else {
                    $strReturn .= '<li><a href="'.$link.'" target="_blank" class="navigation themes" title="">'.$linkTitle.'</a></li>';
                }
            }
            $strReturn .= '</ul></li>';
        }

        return str_replace('<ul class="tl_level_1">', '<ul class="tl_level_1">'.$strReturn, $strContent);
    }
}
