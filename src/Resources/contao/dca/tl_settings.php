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
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{beorder_legend},moduleOrder;{version_legend},checkContaoVersion';


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
$GLOBALS['TL_DCA']['tl_settings']['fields']['checkContaoVersion'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_settings']['checkContaoVersion'],
    'exclude'          => true,
    'inputType'        => 'checkbox',
    'eval'             => ['tl_class' => 'clr w50'],
    'sql'              => "char(1) NOT NULL default ''"
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
        foreach ($GLOBALS['BE_MOD'] as $strKey => $group)
        {
            $mxdName = $GLOBALS['TL_LANG']['MOD'][$strKey];
            $arrModules[$strKey] = (is_array($mxdName)) ? $mxdName[0] : $mxdName;
        }

        return $arrModules;
    }
}
