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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_user']['palettes']['login'] .= ';{redirect_legend},redirect;{om_backend_legend},om_backend_features';
$GLOBALS['TL_DCA']['tl_user']['palettes']['admin'] .= ';{redirect_legend},redirect;{om_backend_legend},om_backend_features';
$GLOBALS['TL_DCA']['tl_user']['palettes']['default'] .= ';{redirect_legend},redirect;{om_backend_legend},om_backend_features';
$GLOBALS['TL_DCA']['tl_user']['palettes']['group'] .= ';{redirect_legend},redirect;{om_backend_legend},om_backend_features';
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] .= ';{redirect_legend},redirect;{om_backend_legend},om_backend_features';
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] .= ';{redirect_legend},redirect;{om_backend_legend},om_backend_features';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['redirect'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['redirect'],
    'inputType' => 'text',
    'eval'      => ['rgxp' => 'url', 'tl_class' => 'w50'],
    'sql'       => "varchar(128) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_user']['fields']['om_backend_features'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_user']['om_backend_features'],
    'inputType'        => 'checkboxWizard',
    'options_callback' => ['tl_user_om_backend', 'getFeatureOptions'],
    'reference'        => &$GLOBALS['TL_LANG']['tl_user']['om_backend_features'],
    'eval'             => ['multiple' => true, 'tl_class' => 'clr'],
    'sql'              => "text NULL"
];


/**
 * Class tl_user
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_user_om_backend extends Backend
{
    /**
     * Get an array of features
     *
     * @return array
     */
    public function getFeatureOptions()
    {
        // feature list
        $arrFeatures = ['addToolbar', 'addLanguage', 'addIdView', 'addCounterView', 'addMarkdownView', 'addBackendLinks', 'addSaveButtons', 'addLayoutButton'];

        // version handling
        $arrPackages = System::getContainer()->getParameter('kernel.packages');
        if (strcmp($arrPackages['contao/core-bundle'], '4.5') < 0)
        {
            $arrFeatures[] = 'addFullWidth';
        }

        // return features
        return $arrFeatures;
    }
}
