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
use Contao\DataContainer;
use OMOSde\ContaoOmBackendBundle\OmBackendElementClassesModel;


/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = ['tl_content_om_backend', 'checkForCssClasses'];


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['cssClasses'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_content']['cssClasses'],
    'exclude'          => true,
    'inputType'        => 'checkboxWizard',
    'options_callback' => ['tl_content_om_backend', 'cssClassesOptionsCallback'],
    'eval'             => ['multiple' => true, 'tl_class' => 'clr'],
    'sql'              => "varchar(255) NOT NULL default ''"
];


/**
 * Class tl_content_om_backend
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_content_om_backend
{
    /**
     * Check for cssClasses
     *
     * @param DataContainer $dc
     */
    public function checkForCssClasses(DataContainer $dc)
    {
        $objElement = \ContentModel::findByPk($dc->id);
        $objCssClasses = OmBackendElementClassesModel::findBy(['type=?', 'element=?'], ['element', $objElement->type]);
        if ($objCssClasses)
        {
            foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $strPalette => $palette)
            {
                if ($strPalette !== '__selector__')
                {
                    $GLOBALS['TL_DCA']['tl_content']['palettes'][$strPalette] = str_replace('cssID', 'cssID,cssClasses', $GLOBALS['TL_DCA']['tl_content']['palettes'][$strPalette]);
                }
            }
        }
    }


    /**
     * @param DataContainer $dc
     *
     * @return array
     */
    public function cssClassesOptionsCallback(DataContainer $dc)
    {
        // search for elements
        $objElements = OmBackendElementClassesModel::findBy(['type=?', 'element=?'], ['element', $dc->activeRecord->type]);
        if (!$objElements)
        {
            return [];
        }

        // get user object
        $objUser = Contao\BackendUser::getInstance();

        // handle found elements
        foreach ($objElements as $element)
        {
            $arrClasses = StringUtil::deserialize($element->classes, true);
            foreach ($arrClasses as $class)
            {
                if (isset($arrReturn[$class['class']]))
                {
                    if ($class['language'] == $objUser->language)
                    {
                        $arrReturn[$class['class']] = $class['text'];
                    }
                }
                else
                {
                    $arrReturn[$class['class']] = $class['text'];
                }

            }
        }

        return $arrReturn;
    }
}
