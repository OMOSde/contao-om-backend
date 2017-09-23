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
 * Table tl_om_backend_links_top
 */
$GLOBALS['TL_DCA']['tl_om_backend_links_top'] = array
(
    // Config
    'config' => array
    (
        'dataContainer' => 'Table',
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
            'mode'        => 1,
            'fields'      => array('title'),
            'flag'        => 1,
            'panelLayout' => 'search,limit'
        ),
        'label' => array
        (
            'fields'         => array('title'),
            'format'         => '%s',
            'label_callback' => array('tl_om_backend_links_top', 'labelCallback'),
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'copy' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ),
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_om_backend_links_top']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array
            (
                'label'           => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('tl_om_backend_links_top', 'toggleIcon')
            ),
            'show' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default' => '{title_legend},title,url,icon;{publish_legend},published'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['title'],
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>50, 'tl_class'=>'w50'),
            'sql'       => "varchar(50) NOT NULL default ''"
        ),
        'url' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['url'],
            'inputType' => 'text',
            'eval'      => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'icon' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['icon'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array('files'=>true, 'filesOnly'=>true, 'extensions'=>\Config::get('validImageTypes'), 'fieldType'=>'radio', 'tl_class'=>'clr'),
            'sql'       => "binary(16) NULL"
        ),
        'published' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_om_backend_links_top']['published'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array(),
            'sql'       => "char(1) NOT NULL default ''"
        )
    )
);


/**
 * Class tl_om_backend_links_top
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class tl_om_backend_links_top extends Backend
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
     * Label callback
     *
     * @param $arrRow
     *
     * @return string
     */
    public function labelCallback($arrRow)
    {
        $strUrl = ($arrRow['url']) ? sprintf(' <span style="color:#999;padding-left:3px">[%s]</span>', $arrRow['url']) : '';

        return sprintf('%s%s', $arrRow['title'], $strUrl);
    }


    /**
     * Return the "toggle visibility" button
     *
     * @param array
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     *
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_backend_links_top::published', 'alexf'))
        {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
    }


    /**
     * Disable/enable an backend link
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to publish
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_om_backend_links_top::published', 'alexf'))
        {
            // logging & redirect
            \System::getContainer()
                ->get('monolog.logger.contao')
                ->log(\LogLevel::ERROR, sprintf('Not enough permissions to publish/unpublish backend link ID "%s"', $intId));

            $this->redirect('contao/main.php?act=error');
        }

        $objVersions = new Versions('tl_om_backend_links_top', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_om_backend_links_top']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_om_backend_links_top']['fields']['published']['save_callback'] as $callback)
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
        $this->Database->prepare("UPDATE tl_om_backend_links_top SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")->execute($intId);

        // create a new version
        $objVersions->create();

        // logging
        \System::getContainer()
            ->get('monolog.logger.contao')
            ->log(\LogLevel::INFO, sprintf('A new version of record "tl_om_backend_links_top.id=%s" has been created %s', $intId, $this->getParentEntries('tl_om_backend_links_top', $intId)));
    }
}
