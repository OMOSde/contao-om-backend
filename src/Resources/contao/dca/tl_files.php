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
