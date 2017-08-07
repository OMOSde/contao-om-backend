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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_files']['palettes']['default'] .= ';{usage_legend},usage';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_files']['fields']['usage']= array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_files']['usage'],
    'inputType' => 'usageWizard',
    'eval'      => array('maxlength'=>255),
);
