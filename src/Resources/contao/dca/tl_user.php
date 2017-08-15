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
$GLOBALS['TL_DCA']['tl_user']['palettes']['login'] .= ';{om_backend_legend},om_backend_features';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['om_backend_features'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_user']['om_backend_features'],
    'inputType'               => 'checkboxWizard',
    'options'                 => array('addToolbar', 'addLanguage', 'addIdView', 'addCounterView', 'addMarkdownView', 'addBackendLinks'),
    'reference'               => &$GLOBALS['TL_LANG']['tl_user']['om_backend_features'],
    'eval'                    => array('multiple' => true, 'tl_class' => 'clr'),
    'sql'                     => "text ''"
);
