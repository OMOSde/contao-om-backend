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
                $strHref = sprintf('contao/main.php?do=%s&amp;tab=%s&amp;table=%s&amp;rt=%s', $strModule, $strTab, $strTable, REQUEST_TOKEN);

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
     * @param $arrModules
     *
     * @return array
     */
    public function changeNavigation($arrModules)
    {
        // variables
        $arrHandle = [];

        // determine tabs to remove and navigation links to add table in href
        foreach ($arrModules as $keyGroup => &$arrGroup)
        {
            foreach ($arrGroup['modules'] as $keyModule => $module)
            {
                if (isset($module['tabs']) && count($module['tabs']) > 0)
                {
                    // modules to remove from navigation
                    foreach ($module['tabs'] as $tab)
                    {
                        $arrHandle['remove'][] = [
                            'group'  => $keyGroup,
                            'module' => $tab
                        ];
                    }

                    // module to add table in href
                    $arrHandle['add'][] = [
                        'group'  => $keyGroup,
                        'module' => $keyModule
                    ];
                }
            }
        }

        // remove modules from navigation
        if (is_array($arrHandle['remove']) && !empty($arrHandle['remove']))
        {
            foreach ($arrHandle['remove'] as $module)
            {
                unset($arrModules[$module['group']]['modules'][$module['module']]);
            }
        }

        // add default table to navigation link
        if (is_array($arrHandle['add']) && !empty($arrHandle['add']))
        {
            foreach ($arrHandle['add'] as $module)
            {
                if (is_array($arrModules[$module['group']]['modules'][$module['module']]['tables']))
                {
                    $arrModules[$module['group']]['modules'][$module['module']]['href'] .= '&table=' . $arrModules[$module['group']]['modules'][$module['module']]['tables'][0];
                }
            }
        }

        return $arrModules;
    }
}
