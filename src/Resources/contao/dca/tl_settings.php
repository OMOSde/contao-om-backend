<?php

/**
 * Contao bundle contao-om-backend
 *
 * @copyright OMOS.de 2019 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      https://github.com/OMOSde/contao-om-backend
 * @license   LGPL 3.0+
 */


/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{beorder_legend},moduleOrder';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['moduleOrder'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_settings']['moduleOrder'],
    'exclude'          => true,
    'inputType'        => 'checkboxWizard',
    'options_callback' => ['tl_settings_om_backend', 'getBackendModules'],
    'eval'             => ['multiple' => true, 'csv' => ',', 'tl_class' => 'clr'],
    'sql'              => "varchar(255) NOT NULL default ''"
];


/**
 * Class tl_settings_om_backend
 *
 * @copyright OMOS.de 2019 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_settings_om_backend
{
    /**
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getBackendModules(DataContainer $dc)
    {
        foreach ($GLOBALS['BE_MOD'] as $strKey => $beMod)
        {
            $arrModules[$strKey] = $GLOBALS['TL_LANG']['MOD'][$strKey];
        }

        return $arrModules;
    }
}
