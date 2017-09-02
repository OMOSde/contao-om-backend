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
     * Adds a class for id view
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed
     */
    public function addBodyClasses($strContent, $strTemplate)
    {

        if ($strTemplate == 'be_main')
        {
            // add id-view class to body
            if (is_array($this->User->om_backend_features) && in_array('addIdView', $this->User->om_backend_features))
            {
                $strContent = str_replace('<body id="top" class="', '<body id="top" class="om_backend_id_view ', $strContent);
            }

            // id charcounter class to body
            if (is_array($this->User->om_backend_features) && in_array('addCounterView', $this->User->om_backend_features))
            {
                $strContent = str_replace('<body id="top" class="', '<body id="top" class="om_backend_counter_view ', $strContent);
            }
        }

        if ($strTemplate == 'be_main' && is_array($this->User->om_backend_features) && in_array('addIdView', $this->User->om_backend_features))
        {
            // add new css class to body
            $strContent = str_replace('<body id="top" class="', '<body id="top" class="om_backend_id_view ', $strContent);
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
        $arrGroups = deserialize($objUser->groups, true);
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
            \Controller::redirect($strBaseUrl.'/contao?'.html_entity_decode($strUrl));
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
        if (\Config::get('addBackendContact') && $strTemplate == 'be_main')
        {
            $strUrl = sprintf('<a href="%s" style="background: url(%s) left 17px no-repeat;">%s</a>',
                \Config::get('om_contact_url'),
                \FilesModel::findByUuid(\Config::get('om_contact_icon'))->path,
                \Config::get('om_contact_title'));

            $strContent = str_replace('<ul id="tmenu">', '<ul id="tmenu">'.$strUrl, $strContent);
        }

        return $strContent;
    }
}
