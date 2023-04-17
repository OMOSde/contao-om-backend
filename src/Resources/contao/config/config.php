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
 * Use
 */
use Contao\BackendUser;
use Contao\Config;
use Contao\Environment;
use Contao\System;

/**
 * Add stylesheets and javascript
 */
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoombackend/css/om_backend.css|static';
    $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoombackend/css/markdown.css|static';
    $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoombackend/plugins/tablesort/css/tablesort.css|static';

    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/omosdecontaoombackend/js/om_backend.js';
}


/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['om_backend'] = [
    'id_search'       => [
        'callback' => 'OMOSde\ContaoOmBackendBundle\ModuleIdSearch',
    ],
    'element_classes' => [
        'tables' => ['tl_om_backend_element_classes']
    ]
];


/**
 * Add selected backend modules
 */
if (TL_MODE == 'BE' && strpos(Environment::get('request'), 'contao/install') === false)
{
    $arrPackages = System::getContainer()->getParameter('kernel.packages');
    if (version_compare($arrPackages['contao/core-bundle'] ?? $arrPackages['contao/contao'], '4.9.0', '>='))
    {
        $objUser = System::getContainer()->get('security.helper')->getUser();

        if (!$objUser instanceof BackendUser)
        {
            $objUser = null;
        }
    }

    if (null === $objUser)
    {
        $objUser = BackendUser::getInstance();
        $objUser->authenticate();
    }

    if ($objUser && $objUser->om_backend_features !== null && in_array('addSysInfo', $objUser->om_backend_features))
    {
//        $GLOBALS['BE_MOD']['om_backend']['sysinfo'] = [
//            'callback' => 'OMOSde\ContaoOmBackendBundle\ModuleBackendTabs',
//            'tabs'     => [
//                'sysinfo_database',
//                'sysinfo_phpinfo',
//                'sysinfo_packages'
//            ]
//        ];
        $GLOBALS['BE_MOD']['om_backend']['sysinfo_database']['callback'] = 'OMOSde\ContaoOmBackendBundle\ModuleSysinfoDatabase';
        $GLOBALS['BE_MOD']['om_backend']['sysinfo_phpinfo']['callback'] = 'OMOSde\ContaoOmBackendBundle\ModuleSysinfoPhpInfo';
        $GLOBALS['BE_MOD']['om_backend']['sysinfo_packages']['callback'] = 'OMOSde\ContaoOmBackendBundle\ModuleSysinfoPackages';

    }

    if ($objUser && $objUser->om_backend_features !== null && in_array('addMarkdownView', $objUser->om_backend_features))
    {
        $GLOBALS['BE_MOD']['om_backend']['markdown_view']['callback'] = 'OMOSde\ContaoOmBackendBundle\ModuleMarkdownViewer';
    }

    if ($objUser && $objUser->om_backend_features !== null && in_array('addBackendLinks', $objUser->om_backend_features))
    {
//        $GLOBALS['BE_MOD']['om_backend']['backend_links'] = [
//            'callback' => 'OMOSde\ContaoOmBackendBundle\ModuleBackendTabs',
//            'tabs'     => [
//                'backend_links_main',
//                'backend_links_top'
//            ]
//        ];
        $GLOBALS['BE_MOD']['om_backend']['backend_links_main'] = [
            'tables' => ['tl_om_backend_links_main']
        ];
        $GLOBALS['BE_MOD']['om_backend']['backend_links_top'] = [
            'tables' => ['tl_om_backend_links_top']
        ];
    }

    // get tables from all backend modules
    foreach ($GLOBALS['BE_MOD'] as $arrModules)
    {
        foreach ($arrModules as $keyModule => $module)
        {
            if (isset($module['tables'])) {
                $arrTables[$keyModule] = $module['tables'];
            }
        }
    }

    // add tables
    foreach ($GLOBALS['BE_MOD'] as $keyGroup => $arrModules)
    {
        foreach ($arrModules as $keyModule => $module)
        {
            if (isset($module['tabs']) && count($module['tabs']))
            {
                $GLOBALS['BE_MOD'][$keyGroup][$keyModule]['tables'] = [];
                foreach ($module['tabs'] as $tab)
                {
                    if (isset($arrTables[$tab]) && is_array($arrTables[$tab]) && !empty($arrTables[$tab]))
                    {
                        $GLOBALS['BE_MOD'][$keyGroup][$keyModule]['tables'] = array_merge($GLOBALS['BE_MOD'][$keyGroup][$keyModule]['tables'], $arrTables[$tab]);
                    }
                }
            }
        }
    }

    /**
     * Backend form fields
     */
    $GLOBALS['BE_FFL']['usageWizard'] = 'OMOSde\ContaoOmBackendBundle\UsageWizard';


    /**
     * Hooks
     */
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = ['OMOSde\ContaoOmBackendBundle\Toolbar', 'addToolbarToBackendTemplate'];
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = ['OMOSde\ContaoOmBackendBundle\Hooks', 'addBodyClasses'];
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = ['OMOSde\ContaoOmBackendBundle\BackendLinks', 'addBackendLinks'];
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = ['OMOSde\ContaoOmBackendBundle\Versions', 'checkContaoVersions'];
    $GLOBALS['TL_HOOKS']['postLogin'][] = ['OMOSde\ContaoOmBackendBundle\Hooks', 'redirectUser'];
    $GLOBALS['TL_HOOKS']['getUserNavigation'][] = ['OMOSde\ContaoOmBackendBundle\Hooks', 'handleModuleOrder'];
    //$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('OMOSde\ContaoOmBackendBundle\BackendLinks', 'addBackendLinksMain');
    $GLOBALS['TL_HOOKS']['getUserNavigation'][] = ['OMOSde\ContaoOmBackendBundle\ModuleBackendTabs', 'changeNavigation'];


    /**
     * Models
     */
    $GLOBALS['TL_MODELS']['tl_om_backend_links_main'] = 'OMOSde\ContaoOmBackendBundle\OmBackendLinksMainModel';
    $GLOBALS['TL_MODELS']['tl_om_backend_links_top'] = 'OMOSde\ContaoOmBackendBundle\OmBackendLinksTopModel';
    $GLOBALS['TL_MODELS']['tl_om_backend_element_classes'] = 'OMOSde\ContaoOmBackendBundle\OmBackendElementClassesModel';
}


// add contao version cronjob
if (Config::get('checkContaoVersion'))
{
    $GLOBALS['TL_CRON']['daily']['checkContaoVersions'] = ['OMOSde\ContaoOmBackendBundle\Versions', 'getContaoVersions'];
}


/**
 * Add on frontend
 */
if (TL_MODE === 'FE' && strpos(Environment::get('request'), 'contao/install') === false)
{
    $GLOBALS['TL_HOOKS']['parseTemplate'][] = ['OMOSde\ContaoOmBackendBundle\ElementClasses', 'addElementClassesToTemplate'];
}
