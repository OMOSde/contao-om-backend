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
 * Class ModuleSysinfoDatabase
 *
 * @copyright René Fehrmann
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class ModuleSysinfoDatabase extends \BackendModule
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_sysinfo_database';


    /**
     * Generate module
     */
    protected function compile()
    {
        $arrTables = [];
        $arrOverall = [];

        // import database class
        $this->import('Database');

        // get table information
        $objDatabase = \Database::getInstance();
        $objTables = $objDatabase->prepare("SHOW TABLE STATUS")->execute();
        if (!$objTables)
        {
            $this->Template->empty = 'Empty database?';

            return;
        }

        // handle all tables
        while ($objTables->next())
        {
            $arrTables[] = [
                'name'      => $objTables->Name,
                'increment' => $objTables->Auto_increment,
                'engine'    => $objTables->Engine,
                'rows'      => $objTables->Rows,
                'size'      => \System::getReadableSize($objTables->Data_length + $objTables->Index_length)
            ];

            $arrOverall['rows'] += $objTables->Rows;
            $arrOverall['size'] += $objTables->Data_length + $objTables->Index_length;
        }

        // format overall size
        $arrOverall['size'] = \System::getReadableSize($arrOverall['size']);

        // set template vars
        $this->Template->tables = $arrTables;
        $this->Template->overall = $arrOverall;
    }
}
