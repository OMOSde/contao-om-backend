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
 * Namespace
 */
namespace OMOSde\ContaoOmBackendBundle;


/**
 * Use
 */
use Contao\System;


/**
 * Class ElementClasses
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class ElementClasses extends System
{
    /**
     * Add classes to template object
     *
     * @param $objTemplate
     */
    public function addElementClassesToTemplate($objTemplate)
    {
        // handle articles and content elements
        if ($objTemplate->getName() === 'mod_article' || strncmp($objTemplate->getName(), 'ce_', 3) === 0  || strncmp($objTemplate->getName(), 'rsce_', 5) === 0)
        {
            $arrClasses = \StringUtil::deserialize($objTemplate->cssClasses, true);
            foreach ($arrClasses as $class)
            {
                $objTemplate->class .= ' ' . $class;
            }
        }
    }
}
