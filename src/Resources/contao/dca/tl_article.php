<?php

/**
 * Contao bundle contao-om-backend
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @link      https://github.com/OMOSde/contao-om-backend
 * @license   LGPL 3.0+
 */


/**
 * Use
 */
use OMOSde\ContaoOmBackendBundle\OmBackendElementClassesModel;


/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_article']['config']['onload_callback'][] = ['tl_article_om_backend', 'checkForCssClasses'];


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_article']['fields']['cssClasses'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_article']['cssClasses'],
    'exclude'          => true,
    'inputType'        => 'checkboxWizard',
    'options_callback' => ['tl_article_om_backend', 'cssClassesOptionsCallback'],
    'eval'             => ['multiple' => true, 'tl_class' => 'clr'],
    'sql'              => "varchar(255) NOT NULL default ''"
];


/**
 * Class tl_article_om_backend
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_article_om_backend
{
    /**
     * Check for cssClasses
     *
     * @param DataContainer $dc
     */
    public function checkForCssClasses(DataContainer $dc)
    {
        $objCssClasses = OmBackendElementClassesModel::findBy(['type=?'], ['article']);
        if ($objCssClasses)
        {
            $GLOBALS['TL_DCA']['tl_article']['palettes']['default'] = str_replace('cssID', 'cssID,cssClasses', $GLOBALS['TL_DCA']['tl_article']['palettes']['default']);
        }
    }


    /**
     * Determine css classes
     */
    public function cssClassesOptionsCallback()
    {
        $objElements = OmBackendElementClassesModel::findByType('article');
        if (!$objElements)
        {
            return [];
        }

        foreach ($objElements as $element)
        {
            $arrClasses = StringUtil::deserialize($element->classes, true);
            foreach ($arrClasses as $arrClass)
            {
                $arrReturn[$arrClass['class']] = $arrClass['text'];
            }
        }

        return $arrReturn;
    }
}
