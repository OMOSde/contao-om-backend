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
 * Class Versions
 *
 * @copyright OMOS.de 2019 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class Versions extends \Backend
{
    /**
     * LTS versions
     */
    private static $arrLtsVersions = ['4.4', '4.9'];


    /**
     * Adds a toolbar to the backend template
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed
     */
    public static function getContaoVersions()
    {
        // get tags
        $objClient = new \Github\Client();
        $arrTags = (new \Github\ResultPager($objClient))->fetchAll($objClient->api('repo'), 'tags', ['contao', 'core-bundle']);

        $arrVersions = [];
        foreach (array_reverse($arrTags) as $arrTag)
        {
            if (strpos($arrTag['name'], '-') === false)
            {
                $arrVersions[] = $arrTag['name'];
            }
        }

        // get latest lts version
        $strLatestLtsVersion = '';
        foreach (self::$arrLtsVersions as $strLtsVersion)
        {
            foreach ($arrVersions as $strVersion)
            {
                if (strpos($strVersion, $strLtsVersion) === 0)
                {
                    $strLatestLtsVersion = $strVersion;
                }
            }
        }

        // save latest versions to config
        \Config::persist('latestLtsVersion', $strLatestLtsVersion);
        \Config::persist('latestVersion', end($arrVersions));
    }


    /**
     * Check contao versions
     */
    public static function checkContaoVersions($strContent, $strTemplate)
    {
        // check template
        if ($strTemplate !== 'be_main' || !\Config::get('checkContaoVersion'))
        {
            return $strContent;
        }

        // check config
        if (!\Config::get('latestVersion') || !\Config::get('latestLtsVersion'))
        {
            return $strContent;
        }

        // get current version
        $arrPackages = \System::getContainer()->getParameter('kernel.packages');
        $strCurrentVersion = (isset($arrPackages['contao/core-bundle']) ? $arrPackages['contao/core-bundle'] : $arrPackages['contao/contao']);

        // is lts?
        $isLts = false;
        foreach (self::$arrLtsVersions as $strLtsVersion)
        {
            if (strpos($strCurrentVersion, $strLtsVersion) !== false)
            {
                $isLts = true;
                break;
            }
        }

        // create version string
        $strVersion = sprintf('%s %s | <span style="color:white;">%s</span>', $GLOBALS['TL_LANG']['MSC']['version'], $strCurrentVersion, ($isLts) ? \Config::get('latestLtsVersion') : \Config::get('latestVersion'));
        $strLearnMore = sprintf($GLOBALS['TL_LANG']['MSC']['learnMore'], '<a href="https://contao.org" target="_blank">contao.org</a>');

        // write version into dom
        $objCrawler = \Wa72\HtmlPageDom\HtmlPageCrawler::create($strContent);
        $objCrawler->filter('div.version')->setInnerHtml($strVersion . '<br>' . $strLearnMore);

        return $objCrawler->saveHTML();
    }
}
