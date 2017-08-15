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
 * Additional DCA
 */
$GLOBALS['TL_DCA']['tl_page']['list']['label']['label_callback'] = array('tl_page_om_backend', 'addLanguage');


/**
 * Class tl_page_om_backend
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class  tl_page_om_backend extends Backend
{
    /**
     * Add an image to each page in the tree
     * @param array
     * @param string
     * @param \DataContainer
     * @param string
     * @param boolean
     * @param boolean
     * @return string
     */
    public function addLanguage($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false)
    {
        $this->import('BackendUser', 'User');
    
        $html = Backend::addPageIcon($row, $label, $dc, $imageAttribute, $blnReturnImage, $blnProtected);

        if (is_array($this->User->om_backend_features) && in_array('addLanguage', $this->User->om_backend_features) && $row['type'] == 'root')
        {
            $html .= sprintf('<strong> (%s)</strong>', strtoupper($row['language']));
        }

        return $html;
    }
}
