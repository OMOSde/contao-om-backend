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
 * Layout button
 */
if (TL_MODE == 'BE' && strpos(Environment::get('request'), 'contao/install') === false)
{
    $objUser = BackendUser::getInstance();
    $objUser->authenticate();

    if (in_array('addLayoutButton', (array) $objUser->om_backend_features))
    {

        $GLOBALS['TL_DCA']['tl_page']['list']['operations']['layout'] = [
            'label'           => &$GLOBALS['TL_LANG']['tl_page']['layout'],
            'icon'            => 'layout.svg',
            'button_callback' => ['tl_page_om_backend', 'generateButtonLayout']
        ];
    }
}


/**
 * Additional DCA
 */
$GLOBALS['TL_DCA']['tl_page']['list']['label']['label_callback'] = ['tl_page_om_backend', 'addLanguage'];


/**
 * Class tl_page_om_backend
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class  tl_page_om_backend extends Backend
{
    /**
     * All pages
     *
     * @var null
     */
    protected static $arrPages = null;

    /**
     * Add an image to each page in the tree
     *
     * @param array
     * @param string
     * @param \DataContainer
     * @param string
     * @param boolean
     * @param boolean
     *
     * @return string
     */
    public function addLanguage($row, $label, DataContainer $dc = null, $imageAttribute = '', $blnReturnImage = false, $blnProtected = false)
    {
        $this->import('BackendUser', 'User');

        $html = Backend::addPageIcon($row, $label, $dc, $imageAttribute, $blnReturnImage, $blnProtected);

        if (is_array($this->User->om_backend_features) && in_array('addLanguage', $this->User->om_backend_features) && $row['type'] == 'root')
        {
            $html .= sprintf('<span class="om_backend_language"> (%s)</span>', strtoupper($row['language']));
        }

        return $html;
    }


    /**
     * Generate the layout button
     *
     * @param $arrRow
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     *
     * @return string
     */
    public function generateButtonLayout($arrRow, $href, $label, $title, $icon, $attributes)
    {
        // check for pages
        if (self::$arrPages === null)
        {
            $objPages = PageModel::findAll();
            foreach ($objPages as $page)
            {
                self::$arrPages[$page->id] = $page->row();
            }
        }

        // get layout by traversing through pages
        $arrPage = self::$arrPages[$arrRow['id']];
        while ($arrPage['layout'] == 0)
        {
            if ($arrPage['type'] == 'root' && $arrPage['layout'] == 0)
            {
                break;
            }

            $arrPage = self::$arrPages[$arrPage['pid']];
        }

        // import backend user
        $this->import('BackendUser', 'User');

        // get layout
        $objLayout = \LayoutModel::findByPk($arrPage['layout']);
        $strTitle = sprintf($GLOBALS['TL_LANG']['tl_page']['layout'][1], '"' . $objLayout->name . '"');

        // return link or image only
        return ($this->User->hasAccess('layout', 'themes') && $arrPage['layout'] > 0) ? '<a href="' . $this->addToUrl('do=themes&table=tl_layout&act=edit&id=' . $arrPage['layout'], true,
                ['do']) . '" title="' . StringUtil::specialchars($strTitle) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ' : Image::getHtml(preg_replace('/\.svg$/i', '.svg', $icon)) . ' ';
    }
}
