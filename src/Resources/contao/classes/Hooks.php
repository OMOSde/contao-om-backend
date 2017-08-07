<?php

/**
 * Contao module om_backend
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @package   om_backend
 * @link      http://www.omos.de
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
