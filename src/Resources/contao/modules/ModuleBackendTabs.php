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
use \Wa72\HtmlPageDom\HtmlPageCrawler;


/**
 * Class ModuleBackendTabs
 *
 * @copyright René Fehrmann
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class ModuleBackendTabs extends \BackendModule
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_backend_tabs';


    /**
     * Generate module
     */
    protected function compile()
    {
        // variable
        $strManager = '';
        $arrModule = [];
        $strModule = \Input::get('do');

        // handle all backend modules
        foreach ($GLOBALS['BE_MOD'] as &$arrGroup)
        {
            if (isset($arrGroup[$strModule]))
            {
                $arrModule =& $arrGroup[$strModule];
                break;
            }
        }

        // generate manager
        if (isset($arrModule['tabs']) && count($arrModule['tabs']) > 0)
        {
            $strManager = '<div id="manager"><ul>';
            foreach ($arrModule['tabs'] as $intKey => $strTab)
            {
                // get group
                foreach ($GLOBALS['BE_MOD'] as $keyGroup => $arrModules)
                {
                    foreach ($arrModules as $keyModule => $module)
                    {
                        if ($strTab == $keyModule)
                        {
                            $strGroup = $keyGroup;
                            break 2;
                        }
                    }
                }

                // generate link
                $strTable = sprintf('%s', $GLOBALS['BE_MOD'][$strGroup][$strTab]['tables'][0]);
                $strHref = sprintf('%scontao?do=%s&tab=%s&table=%s', (strpos(\Environment::get('request'), 'app_dev.php') !== false) ? 'app_dev.php/' : '', $strModule, $strTab, $strTable);

                // add class
                if (!\Input::get('tab'))
                {
                    $strClass = ($intKey == 0) ? ' class="current"' : '';
                }
                else
                {
                    $strClass = (\Input::get('tab') == $strTab) ? ' class="current"' : '';
                }

                // list item
                $strManager .= sprintf('<li%s style="margin-right:4px;"><a href="%s" title="%s">%s</a></li>', $strClass, $strHref, $GLOBALS['TL_LANG']['MOD'][$strTab][1], $GLOBALS['TL_LANG']['MOD'][$strTab][0]);
            }
            $strManager .= '</ul></div>';
        }

        // set template vars
        $this->Template->manager = $strManager;
        $this->Template->html = $this->getBackendModule((\Input::get('tab')) ?: $arrModule['tabs'][0]);
    }


    /**
     * Removes modules in the navigation, which are used in tabs
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed
     */
    public function changeNavigation($strContent, $strTemplate)
    {
        if ($strTemplate != 'be_main')
        {
            return $strContent;
        }

        // variables
        $arrTabs = [];
        $arrModules = [];

        // determine tabs to remove
        foreach ($GLOBALS['BE_MOD'] as $keyGroup => &$arrGroup)
        {
            foreach ($arrGroup as $keyModule => $module)
            {
                if (isset($module['tabs']) && count($module['tabs']) > 0)
                {
                    foreach ($module['tabs'] as $tab)
                    {
                        $arrTabs[] = $tab;
                    }

                    $arrModules[] = [
                        'group'  => $keyGroup,
                        'module' => $keyModule
                    ];
                }
            }
        }
        $arrTabs = array_unique($arrTabs);

        // get dom
        $objCrawler = HtmlPageCrawler::create($strContent);

        // remove tabs from dom
        foreach ($arrTabs as $tab)
        {
            $objCrawler->filter('#tl_navigation .navigation.' . $tab)->remove();
        }

        // add table to backend links
        foreach ($arrModules as $module)
        {
            $arrLinks = $objCrawler->filter('#tl_navigation .navigation.' . $module['module']);
            foreach ($arrLinks as $link)
            {
                $link->setAttribute('href', $link->getAttribute('href') . '&table=' . $GLOBALS['BE_MOD'][$module['group']][$module['module']]['tables'][0]);
            }
        }

        return $objCrawler->saveHTML();
    }
}
