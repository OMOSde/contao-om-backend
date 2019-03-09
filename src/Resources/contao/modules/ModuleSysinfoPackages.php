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
 * Class ModuleSysinfoPackages
 *
 * @copyright René Fehrmann
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class ModuleSysinfoPackages extends \BackendModule
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_sysinfo_packages';


    /**
     * Generate module
     */
    protected function compile()
    {
        // get all installed packages
        $arrPackages = \System::getContainer()->getParameter('kernel.packages');
        if (!is_array($arrPackages) || empty($arrPackages))
        {
            $this->Template->empty = $GLOBALS;

            return;
        }

        // set template vars
        $this->Template->packages = $arrPackages;
        $this->Template->info = sprintf('%s: %s', $GLOBALS['TL_LANG']['om_backend']['sysinfo']['packages'], count($arrPackages));
    }
}
