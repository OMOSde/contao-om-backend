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
 * Table tl_om_backend_element_classes
 */
$GLOBALS['TL_DCA']['tl_om_backend_element_classes'] = [
    // Config
    'config'   => [
        'dataContainer' => 'Table',
        'sql'           => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    // List
    'list'     => [
        'sorting'           => [
            'mode'        => 1,
            'fields'      => ['type'],
            'flag'        => 1,
            'panelLayout' => 'filter,limit'
        ],
        'label'             => [
            'fields'         => ['title'],
            'format'         => '%s',
            'label_callback' => ['tl_om_backend_element_classes', 'labelCallback']
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_om_backend_element_classes']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ]
        ]
    ],

    // Palettes
    'palettes' => [
        '__selector__' => ['type'],
        'default'      => '{type_legend},type;{element_legend},element;{class_legend},classes',
        'article'      => '{type_legend},type;{class_legend},classes',
        'element'      => '{type_legend},type;{element_legend},element;{class_legend},classes'
    ],

    // Fields
    'fields'   => [
        'id'      => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp'  => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'type'    => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['type'],
            'inputType' => 'select',
            'options'   => ['element', 'article'],
            'reference' => $GLOBALS['TL_LANG']['tl_om_backend_element_classes']['types'],
            'eval'      => ['mandatory' => true, 'submitOnChange' => true, 'tl_class' => 'w50'],
            'sql'       => "varchar(8) NOT NULL default ''"
        ],
        'element' => [
            'label'            => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['element'],
            'inputType'        => 'select',
            'options_callback' => ['tl_om_backend_element_classes', 'getContentElements'],
            'reference'        => &$GLOBALS['TL_LANG']['CTE'],
            'eval'             => ['mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'              => "varchar(32) NOT NULL default ''"
        ],
        'classes' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['classes'],
            'exclude'   => true,
            'inputType' => 'multiColumnWizard',
            'eval'      => [
                'columnFields' => [
                    'class'    => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['class'],
                        'exclude'   => true,
                        'inputType' => 'text',
                        'eval'      => ['style' => 'width:100px']
                    ],
                    'language' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['language'],
                        'exclude'   => true,
                        'inputType' => 'select',
                        'options'   => \System::getLanguages(),
                        'eval'      => ['style' => 'width:100px', 'includeBlankOption' => true, 'chosen' => true]
                    ],
                    'text'     => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_element_classes']['text'],
                        'exclude'   => true,
                        'inputType' => 'text',
                        'eval'      => ['style' => 'width:600px']
                    ],
                ]
            ],
            'sql'       => "text NULL"
        ]
    ]
];


/**
 * Class tl_om_backend_element_classes
 *
 * @copyright OMOS.de 2018 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_om_backend_element_classes extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }


    /**
     * Add an priority class to each record
     *
     * @param array
     * @param string
     * @param \DataContainer
     * @param array
     *
     * @return string
     */
    public function labelCallback($arrRow)
    {
        $strLabel = '';

        switch ($arrRow['type'])
        {
            case 'element':
                $strLabel = sprintf('Inhaltselement - %s', $GLOBALS['TL_LANG']['CTE'][$arrRow['element']][0]);
                break;

            case 'article':
                $strLabel = sprintf('Artikel');
                break;

            default:
                break;
        }

        return $strLabel;
    }


    /**
     * Return all content elements as array
     *
     * @return array
     */
    public function getContentElements()
    {
        $groups = [];

        foreach ($GLOBALS['TL_CTE'] as $k => $v)
        {
            foreach (array_keys($v) as $kk)
            {
                $groups[$k][] = $kk;
            }
        }

        return $groups;
    }
}
