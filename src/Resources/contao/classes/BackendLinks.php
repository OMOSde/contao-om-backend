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
 * Use
 */
use Backend;


/**
 * Class BackendLinks
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class BackendLinks extends Backend
{
    /**
     * Hooks constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->import('BackendUser', 'User');
    }


    /**
     * Manipulate DOM for additional backend links
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed|string
     */
    public function addBackendLinks($strContent, $strTemplate)
    {
        if ($strTemplate != 'be_main')
        {
            return $strContent;
        }

        $strContent = $this->addBackendLinksTop($strContent);
        $strContent = $this->addBackendLinksMain($strContent);

        return $strContent;
    }


    /**
     * Add a backend contact link in top navigation
     *
     * @param $strContent
     *
     * @return mixed
     * @internal param $strTemplate
     *
     */
    protected function addBackendLinksTop($strContent)
    {
        $objLinks = OmBackendLinksTopModel::findByPublished(1);
        if (!$objLinks)
        {
            return $strContent;
        }

        $strLinks = '';
        foreach ($objLinks as $link)
        {
            $strStyle = ($link->icon) ? sprintf('background:url(%s) left 13px no-repeat;', \FilesModel::findByUuid($link->icon)->path) : sprintf('display:inline-block;margin-left:18px;padding: 13px 10px;');
            $strLink = ($link->url) ? sprintf('<li><a href="%s"%s style="%s">%s</a></li>', $link->url, ($link->target) ? ' target="_blank"' : '' ,$strStyle, $link->title) : sprintf('<li><span style="%s">%s</span></li>', $strStyle, $link->title);

            $strLinks .= $strLink;
        }

        $strContent = str_replace('<ul id="tmenu">', '<ul id="tmenu">' . $strLinks, $strContent);

        return $strContent;
    }


    /**
     * Generate backend links
     *
     * @param $strContent
     *
     * @return string ;
     */
    protected function addBackendLinksMain($strContent)
    {
        // get all links
        $objLinks = OmBackendLinksMainModel::findBy(['language=?', 'published=1'], [$this->User->language]);
        if (!$objLinks)
        {
            return $strContent;
        }

        foreach ($objLinks as $link)
        {
            $arrGroups[$link->be_group][$link->title] = $link->url;
        }

        $strReturn = '';
        foreach ($arrGroups as $groupName => $group)
        {
            $strReturn .= '<li class="tl_level_1_group"><a href="contao/main.php?do=repository_manager&amp;mtg=' . $groupName . '" title="" onclick="return AjaxRequest.toggleNavigation(this,\'' . $groupName . '\')">' . $groupName . '</a></li>';
            $strReturn .= '<li class="tl_parent" id="' . $groupName . '" style="display: inline;"><ul class="tl_level_2">';
            foreach ($group as $linkTitle => $link)
            {
                if (strpos($link, 'contao?do') !== false)
                {
                    $container = \System::getContainer();
                    $strToken = $container->get('security.csrf.token_manager')->getToken($container->getParameter('contao.csrf_token_name'))->getValue();

                    $strReturn .= sprintf('<li><a href="%s&rt=%s" class="navigation themes" title="">%s</a></li>', $link, $strToken, $linkTitle);
                }
                else
                {
                    $strReturn .= sprintf('<li><a href="%s" target="_blank" class="navigation themes" title="">%s</a></li>', $link, $linkTitle);
                }
            }
            $strReturn .= '</ul></li>';
        }

        $strContent = str_replace('<ul class="tl_level_1">', '<ul class="tl_level_1">' . $strReturn, $strContent);

        return $strContent;
    }
}
