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
$GLOBALS['TL_DCA']['tl_user_group']['palettes']['default'] = str_replace(',name;', ',name;{redirect_legend},redirect;', $GLOBALS['TL_DCA']['tl_user_group']['palettes']['default']);


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user_group']['fields']['redirect'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_user_group']['redirect'],
    'inputType'               => 'text',
    'eval'                    => array('rgxp' => 'url', 'tl_class' => 'w50'),
    'sql'                     => "varchar(128) NOT NULL default ''"
);
