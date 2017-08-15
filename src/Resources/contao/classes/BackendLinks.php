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
        if ($strTemplate == 'be_main')
        {
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
        }

        return $strContent;
    }
}
