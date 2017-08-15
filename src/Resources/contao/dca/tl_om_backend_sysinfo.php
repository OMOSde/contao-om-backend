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
 * Table tl_om_backend_sysinfo
 */
$GLOBALS['TL_DCA']['tl_om_backend_sysinfo'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 1,
            'fields'                  => array('tstamp'),
            'flag'                    => 7,
            'panelLayout'             => 'filter,limit'
        ),
        'label' => array
        (
            'fields'                  => array('title'),
            'format'                  => '%s',
            'label_callback'          => array('tl_om_backend_sysinfo', 'label_callback')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_faq_category']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},title;{info_legend},info;{priority_legend},priority'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo']['title'],
            'inputType'               => 'text',
            'eval'                    => array('mandatory' => true, 'maxlength' => 120, 'tl_class' => 'long'),
            'sql'                     => "varchar(120) NOT NULL default ''"
        ),
        'priority' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo']['priority'],
            'filter'                  => true,
            'inputType'               => 'select',
            'eval'                    => array('mandatory' => true),
            'options'                 => array('low', 'middle', 'high'),
            'reference'               => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo'],
            'sql'                     => "varchar(12) NOT NULL default ''"
        ),
        'info' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_sysinfo']['info'],
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory' => true, 'rte' => 'tinyMCE'),
            'sql'                     => "text NULL"
        )
    )
);


/**
 * Class tl_om_backend_sysinfo
 * 
 * @copyright OMOS.de 2015 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @package   om_backend
 * @link      http://www.omos.de
 * @license   LGPL
 */
class tl_om_backend_sysinfo extends Backend
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
     * @param array
     * @param string
     * @param \DataContainer
     * @param array
     * @return string
     */
    public function label_callback($row, $label, DataContainer $dc, $args)
    {
        return '<div class="'.$row['priority'].'">'.$row['title'].'</div>';
    }
}
