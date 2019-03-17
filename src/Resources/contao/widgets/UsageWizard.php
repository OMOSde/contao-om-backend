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
 * Namespace
 */
namespace OMOSde\ContaoOmBackendBundle;


/**
 * Provide informations about file usage
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class UsageWizard extends \Widget
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';
    

    /**
     * Generate the widget and return it as string
     *
     * @return string
     */
    public function generate()
    {
        // only on act edit
        if (\Input::get('act') !== 'edit')
        {
            return '';
        }

        // show usage
        if (\Input::get('usage'))
        {
            return $this->searchUsage();
        }

        // show link only
        $container = \System::getContainer();
        $strToken = $container->get('security.csrf.token_manager')->getToken($container->getParameter('contao.csrf_token_name'))->getValue();

        $strEntryPoint = (strpos(\Environment::get('request'), 'app_dev.php') !== false) ? 'app_dev.php/' : '';
        return sprintf('<a href="%scontao?do=files&act=edit&id=%s&usage=1&rt=%s" class="tl_submit">%s</a>',
            $strEntryPoint,
            \Input::get('id'),
            $strToken,
            $GLOBALS['TL_LANG']['tl_files']['button_usage']
        );
    }


    /**
     * Search for uuid in all dca tables
     *
     * @return string
     */
    protected function searchUsage()
    {
        // variables
        $arrTables = [];
        $arrFields = [];

        // imports
        $this->import('Database');

        // get all tables from backend modules
        foreach ($GLOBALS['BE_MOD'] as $group=>$modules)
        {
            foreach ($modules as $key=>$module)
            {
                if (isset($module['tables']) && count($module['tables']) > 0)
                {
                    foreach ($module['tables'] as $table)
                    {
                        if ($GLOBALS['TL_DCA'][$table]['config']['dataContainer'] == 'Table')
                        {
                            $arrTables[$table] = $key;
                        }
                    }
                }
            }
        }

        // get all fields with inputtype fileTree
        foreach ($arrTables as $table=>$modules)
        {
            \Controller::loadDataContainer($table);
            foreach ($GLOBALS['TL_DCA'][$table]['fields'] as $fieldName=>$field)
            {
                if ($field['inputType'] == 'fileTree' && ($field['eval']['files'] || $field['eval']['filesOnly']))
                {
                    if (!is_array($arrFields[$table]) || !in_array($fieldName, $arrFields[$table]))
                    {
                        $arrFields[$table][] = $fieldName;
                    }
                }
            }
        }

        // get database object
        $objDatabase = \Database::getInstance();

        // get uuid to search
        $strUuid = \StringUtil::binToUuid(\FilesModel::findByPath(\Input::get('id'))->uuid);

        // search for uuids
        foreach ($arrFields as $table=>$fields)
        {
            $strQuery = sprintf('SELECT %s FROM %s',
                sprintf('id,%s', implode(',', $fields)),
                $table
            );

            $objResult = $objDatabase->prepare($strQuery)->execute();
            if ($objResult->numRows)
            {
                while ($objResult->next())
                {
                    foreach ($fields as $field)
                    {
                        if ($GLOBALS['TL_DCA'][$table]['fields'][$field]['eval']['multiple'])
                        {
                            $arrFiles = deserialize($objResult->$field, true);
                            foreach ($arrFiles as $file)
                            {
                                if (\StringUtil::binToUuid($file) == $strUuid)
                                {
                                    $arrReferences[] = array
                                    (
                                        'table' => $table,
                                        'field' => $field,
                                        'id'    => $objResult->id
                                    );
                                }
                            }
                        }
                        else
                        {
                            if ($objResult->$field != null && \StringUtil::binToUuid($objResult->$field) == $strUuid)
                            {
                                $arrReferences[] = array
                                (
                                    'table' => $table,
                                    'field' => $field,
                                    'id'    => $objResult->id
                                );
                            }
                        }
                    }
                }
            }
        }

        // no references
        if (!is_array($arrReferences))
        {
            return 'Keine Einträge gefunden!';
        }

        // generate references html
        $strReturn = '<table class="tl_listing"><tbody><tr><td class="tl_folder_tlist">Tabelle</td><td class="tl_folder_tlist">Feld</td><td class="tl_folder_tlist">ID</td><td class="tl_folder_tlist"></td>';
        foreach ($arrReferences as $reference)
        {
            $strReturn .= sprintf('</tr><tr class="even click2edit toggle_select hover-row"><td class="tl_file_list">%s</td><td class="tl_file_list">%s</td><td class="tl_file_list">%s</td><td class="tl_file_list tl_right_nowrap"><a href="contao?do=%s&amp;table=%s&amp;act=edit&amp;id=%s&amp;rt=Qx-t-7MRptSkoUv3wmOJ0bMYKMoSyo7bpXhrRTZ5VkI&amp;ref=dFHbuxqy" title="" class="edit"><img src="system/themes/flexible/icons/edit.svg" width="16" height="16" alt="edit"></a></td></tr>',
                $reference['table'],
                $reference['field'],
                $reference['id'],
                $arrTables[$reference['table']],
                $reference['table'],
                $reference['id']
            );
        }
        $strReturn .= '</tbody></table>';

        return $strReturn;
    }
}
