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
 * Class ModuleIdSearch
 */
class ModuleIdSearch extends \BackendModule
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_id_search';


    /**
     * Generate module
     */
    protected function compile()
    {
        // declare variables
        $arrGroups = null;

        // get tables from database
        $arrTables = $this->Database->listTables();

        // get all dca tables
        foreach ($GLOBALS['BE_MOD'] as $groupName => $group)
        {
            foreach ($group as $moduleName => $modules)
            {
                if (isset($modules['tables']) && is_array($modules['tables']))
                {
                    foreach ($modules['tables'] as $table)
                    {
                        if (in_array($table, $arrTables))
                        {
                            $arrGroups[$groupName]['title']    = $GLOBALS['TL_LANG']['MOD'][$groupName];
                            $arrGroups[$groupName]['tables'][] = array($table, $GLOBALS['TL_LANG']['MOD'][$moduleName][0], $moduleName);
                        }
                    }
                }
            }
        }

        // handle submit
        if (\Input::post('FORM_SUBMIT') == 'om_backend_id_search' && \Input::post('id_alias'))
        {
            // handle post data
            $arrSelected = explode('::', \Input::post('table'));

            // check table for alias field
            $objAlias = $this->Database->prepare("SHOW COLUMNS FROM ".$arrSelected[1]." LIKE 'alias'")->execute();
            if ($objAlias->numRows)
            {
                // get data
                $objData = $this->Database->prepare("SELECT * FROM ".$arrSelected[1]." WHERE id=? or alias=?")->execute(\Input::post('id_alias'), \Input::post('id_alias'));
            } else {
                // get data
                $objData = $this->Database->prepare("SELECT * FROM ".$arrSelected[1]." WHERE id=?")->execute(\Input::post('id_alias'));
            }

            // id or alias exists
            if ($objData->numRows)
            {
                // handle entry point app_dev.php
                $strEntryPoint = (strpos(\Environment::get('request'), 'app_dev.php') !== false) ? 'app_dev.php/' : '';

                // get a request token from csrf service
                $container = \System::getContainer();
                $arrPackages = $container->getParameter('kernel.packages');
                if ($arrPackages['contao/core-bundle'] >= '4.9.0')
                {
                    $objTokenManager = $container->get('contao.csrf.token_manager');
                } else {
                    $objTokenManager = $container->get('security.csrf.token_manager');
                }

                $strToken = $objTokenManager->getToken($container->getParameter('contao.csrf_token_name'))->getValue();

                $strUrl = sprintf('%scontao?do=%s&table=%s&act=edit&id=%s&rt=%s',
                    $strEntryPoint,
                    $arrSelected[0],
                    $arrSelected[1],
                    $objData->id,
                    $strToken
                );

                // redirect
                \Controller::redirect($strUrl);
            } else {
                // error
                $this->Template->id       = \Input::post('id_alias');
                $this->Template->selected = $arrSelected[1];
                $this->Template->error    = sprintf('%s', $GLOBALS['TL_LANG']['ERR']['id_search']['id_or_alias_not_found']);
            }       
        }

        // set template vars
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
        $this->Template->title  = \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['backBT']);
        $this->Template->groups = $arrGroups;
        $this->Template->lang   = $GLOBALS['TL_LANG']['id_search'];
    }
}
