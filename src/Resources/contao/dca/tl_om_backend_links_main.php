<?php

/**
 * Contao module om_backend
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 * @package   om_backend
 * @link      http://www.omos.de
 * @license   LGPL 3.0+
 */


/**
 * Table tl_om_backend_links_main
 */
$GLOBALS['TL_DCA']['tl_om_backend_links_main'] = array
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
            'fields'                  => array('be_group'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('title'),
            'format'                  => '%s',
            'group_callback'          => array('tl_om_backend_links_main', 'groupCallback')
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
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_om_backend_links_main']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['toggle'],
                'icon'                => 'visible.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('tl_om_backend_links_main', 'toggleIcon')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},title,be_group,url,language;{publish_legend},published'
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
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['title'],
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>50, 'tl_class'=>'w50'),
            'sql'                     => "varchar(50) NOT NULL default ''"
        ),
        'be_group' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['be_group'],
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>50, 'tl_class'=>'w50'),
            'sql'                     => "varchar(50) NOT NULL default ''"
        ),
        'url' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['url'],
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'language' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['language'],
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>2, 'tl_class'=>'w50'),
            'sql'                     => "varchar(2) NOT NULL default ''"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_om_backend_links_main']['published'],
            'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange'=>true, 'doNotCopy'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        )
    )
);


/**
 * Class tl_om_backend_links_main
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_om_backend_links_main extends Backend
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
     * Group callback
     * @param $group
     * @param $mode
     * @param $field
     * @param $row
     * @return string
     */
    public function groupCallback($group, $mode, $field, $row)
    {
        return $row['be_group'] . ' - ' . $row['language'];
    }

    /**
     * Return the "toggle visibility" button
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_backend_links_main::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
    }


    /**
     * Disable/enable an backend link
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_backend_links_main::published', 'alexf'))
        {
            // logging & redirect
            \System::getContainer()
                ->get('monolog.logger.contao')
                ->log(LogLevel::ERRO, sprintf('Not enough permissions to publish/unpublish backend link ID "%s"', $intId));

            $this->redirect('contao/main.php?act=error');
        }

        $objVersions = new Versions('tl_om_backend_links_main', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_om_backend_links_main']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_om_backend_links_main']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $this);
                }
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_om_backend_links_main SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);

        // create a new version
        $objVersions->create();

        // logging
        \System::getContainer()
            ->get('monolog.logger.contao')
            ->log(LogLevel::INFO, sprintf('A new version of record "tl_om_backend_links_main.id=%s" has been created %s', $intId, $this->getParentEntries('tl_om_backend_links_main', $intId)));
    }
}
