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
use Contao\Config;
use Contao\FilesModel;


/**
 * Class Toolbar
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class Hooks extends \Backend
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
     * Adds body classes for backend features
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed
     */
    public function addBodyClasses($strContent, $strTemplate)
    {
        // only backend template
        if ($strTemplate == 'be_main')
        {
            $strClasses = '';

            foreach ((array) $this->User->om_backend_features as $feature)
            {
                switch ($feature)
                {
                    case 'addIdView':
                        $strClasses .= 'om_backend_id_view ';
                        break;

                    case 'addCounterView':
                        $strClasses .= 'om_backend_counter_view ';
                        break;

                    case 'addSaveButtons':
                        $strClasses .= 'om_backend_save_buttons ';
                        break;

                    case 'addFullWidth':
                        $strClasses .= 'om_backend_full_width ';
                        break;
                }
            }

            $strContent = str_replace('<body id="top" class="', '<body id="top" class="' . $strClasses, $strContent);
        }

        return $strContent;
    }


    /**
     * Redirect user
     *
     * @param \User $objUser
     */
    public function redirectUser(\User $objUser)
    {
        $strUrl = '';

        // user groups
        $arrGroups = StringUtil::deserialize($objUser->groups, true);
        foreach ($arrGroups as $group)
        {
            $objGroup = \UserGroupModel::findByPk($group);
            if (strlen($objGroup->redirect))
            {
                $strUrl = $objGroup->redirect;
                break;
            }
        }

        // user settings
        if (strlen($objUser->redirect))
        {
            $strUrl = $objUser->redirect;
        }

        // redirect
        if (strlen($strUrl))
        {
            $strBaseUrl = \System::getContainer()->get('request_stack')->getCurrentRequest()->getBaseUrl();
            \Controller::redirect($strBaseUrl . '/contao?' . html_entity_decode($strUrl));
        }
    }


    /**
     * Add a backend contact link in top navigation
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed
     */
    public function addBackendContact($strContent, $strTemplate)
    {
        if (Config::get('addBackendContact') && $strTemplate == 'be_main')
        {
            $strUrl = sprintf('<a href="%s" style="background: url("%s") left 13px no-repeat;">%s</a>', Config::get('om_contact_url'), FilesModel::findByUuid(Config::get('om_contact_icon'))->path, Config::get('om_contact_title'));

            $strContent = str_replace('<ul id="tmenu">', '<ul id="tmenu">' . $strUrl, $strContent);
        }

        return $strContent;
    }


    /**
     * Handle order of backend modules
     *
     * @param $arrModules
     *
     * @return array
     */
    public function handleModuleOrder($arrModules)
    {
        return array_merge(array_flip(\StringUtil::deserialize(\Config::get('moduleOrder'))), $arrModules);
    }
}
