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
 * Add stylesheets and javascript
 */
if (TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoombackend/css/om_backend.css|static';
    $GLOBALS['TL_CSS'][] = 'bundles/omosdecontaoombackend/css/markdown.css|static';

    $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/omosdecontaoombackend/js/om_backend.js';
}


/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['om_backend'] = array
(
    'id_search' => array
    (
        'callback' => 'OMOSde\ContaoOmBackendBundle\ModuleIdSearch',
    ),
    /*'sysinfo' => array
    (
        'tables'   => array('tl_om_backend_sysinfo'),
    ),*/
);


/**
 * Add selected backend modules
 */
if (TL_MODE == 'BE')
{
    $objUser = BackendUser::getInstance();
    $objUser->authenticate();

    if ($objUser->om_backend_features !== null && in_array('addMarkdownView', $objUser->om_backend_features))
    {
        $GLOBALS['BE_MOD']['om_backend']['markdown_view']['callback'] = 'OMOSde\ContaoOmBackendBundle\ModuleMarkdownViewer';
    }
    if ($objUser->om_backend_features !== null && in_array('addBackendLinks', $objUser->om_backend_features))
    {
        $GLOBALS['BE_MOD']['om_backend']['backend_links'] = array
        (
            'callback' => 'OMOSde\ContaoOmBackendBundle\ModuleBackendTabs',
            'tabs'     => array
            (
                'backend_links_main',
                'backend_links_top'
            )
        );
        $GLOBALS['BE_MOD']['om_backend']['backend_links_main'] = array
        (
            'tables' => array('tl_om_backend_links_main')
        );
        $GLOBALS['BE_MOD']['om_backend']['backend_links_top'] = array
        (
            'tables' => array('tl_om_backend_links_top')
        );
    }

    // get tables from all backend modules
    foreach ($GLOBALS['BE_MOD'] as $arrModules)
    {
        foreach ($arrModules as $keyModule=>$module)
        {
            $arrTables[$keyModule] = $module['tables'];
        }
    }

    // add tables
    foreach ($GLOBALS['BE_MOD'] as $keyGroup=>$arrModules)
    {
        foreach ($arrModules as $keyModule=>$module)
        {
            if (isset($module['tabs']) && count($module['tabs']))
            {
                $GLOBALS['BE_MOD'][$keyGroup][$keyModule]['tables'] = array();
                foreach ($module['tabs'] as $tab)
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
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('OMOSde\ContaoOmBackendBundle\Toolbar', 'addToolbarToBackendTemplate');
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('OMOSde\ContaoOmBackendBundle\ModuleBackendTabs', 'changeNavigation');
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('OMOSde\ContaoOmBackendBundle\Hooks', 'addBodyClasses');
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('OMOSde\ContaoOmBackendBundle\BackendLinks', 'addBackendLinks');
//$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('OMOSde\ContaoOmBackendBundle\BackendLinks', 'addBackendLinksMain');


/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_om_backend_links_main'] = 'OMOSde\ContaoOmBackendBundle\OmBackendLinksMainModel';
$GLOBALS['TL_MODELS']['tl_om_backend_links_top']  = 'OMOSde\ContaoOmBackendBundle\OmBackendLinksTopModel';
