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
 * Usages
 */
use Contao\ThemeModel;


/**
 * Class Toolbar
 *
 * @copyright OMOS.de 2017 <http://www.omos.de>
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class Toolbar extends \Backend
{
    /**
     * Toolbar constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }


    /**
     * Adds a toolbar to the backend template
     *
     * @param $strContent
     * @param $strTemplate
     *
     * @return mixed
     */
    public function addToolbarToBackendTemplate($strContent, $strTemplate)
    {
        if ($strTemplate == 'be_main' && is_array($this->User->om_backend_features) && in_array('addToolbar', $this->User->om_backend_features))
        {
            // add new css class to body
            $strContent = str_replace('<body id="top" class="', '<body id="top" class="om_backend_toolbar ', $strContent);

            // add html
            $strContent = str_replace('<div id="container"', $this->generateToolbar($strContent).'<div id="container"' , $strContent);
        }

        return $strContent;
    }


    /**
     * Generate the html for the toolbar
     *
     * @param $strContent
     * @return string
     */
    protected function generateToolbar($strContent)
    {
        // handle entry point app_dev.php
        $strEntryPoint = (strpos(\Environment::get('request'), 'app_dev.php') !== false) ? 'app_dev.php/' : '';

        // get a request token from csrf service
        $container = \System::getContainer();
        $strToken = $container->get('security.csrf.token_manager')->getToken($container->getParameter('contao.csrf_token_name'))->getValue();

        // start html
        $strToolbar  = '<div id="om_backend_toolbar">';

        // add buttons id-search, install-tool, new template, sync files
        $strToolbar .= sprintf('<a class="button" href="%scontao?do=id_search" title="%s">%s</a>',
            $strEntryPoint,
            $GLOBALS['TL_LANG']['MSC']['om_backend']['id_search'],
            $this->getIcon('idSearch')
        );
        $strToolbar .= sprintf('<a class="button" href="%scontao/install" target="_blank" title="%s">%s</a>',
            $strEntryPoint,
            $GLOBALS['TL_LANG']['MSC']['om_backend']['install_tool'],
            $this->getIcon('installTool')
        );
        $strToolbar .= sprintf('<a class="button" href="%scontao?do=tpl_editor&key=new_tpl&rt=%s" title="%s">%s</a>',
            $strEntryPoint,
            $strToken,
            $GLOBALS['TL_LANG']['MSC']['om_backend']['new_template'],
            $this->getIcon('newTemplate')
        );
        $strToolbar .= sprintf('<a class="button" href="%scontao?do=files&act=sync&rt=%s" title="%s">%s</a>',
            $strEntryPoint,
            $strToken,
            $GLOBALS['TL_LANG']['MSC']['om_backend']['sync_files'],
            $this->getIcon('syncFiles')
        );

        // add sections stylesheets, modules, layouts, image_sizes from themes
        $objThemes = ThemeModel::findAll(array('order'=>'name'));
        if ($objThemes)
        {
            foreach ($objThemes as $theme)
            {
                // add separator
                $strToolbar .= '<div class="separator"></div>';

                // add buttons
                $strToolbar .= sprintf('<a class="button" href="%scontao?do=themes&amp;table=tl_style_sheet&amp;id=%s&amp;rt=%s" title="%s">%s</a>',
                    $strEntryPoint,
                    $objThemes->id,
                    $strToken,
                    sprintf('%s (%s)', $GLOBALS['TL_LANG']['MSC']['om_backend']['stylesheets'], $theme->name),
                    $this->getIcon('themeStylesheet')
                );
                $strToolbar .= sprintf('<a class="button" href="%scontao?do=themes&amp;table=tl_module&amp;id=%s&amp;rt=%s" title="%s">%s</a>',
                    $strEntryPoint,
                    $objThemes->id,
                    $strToken,
                    sprintf('%s (%s)', $GLOBALS['TL_LANG']['MSC']['om_backend']['modules'], $theme->name),
                    $this->getIcon('themeModule')
                );
                $strToolbar .= sprintf('<a class="button" href="%scontao?do=themes&amp;table=tl_layout&amp;id=%s&amp;rt=%s" title="%s">%s</a>',
                    $strEntryPoint,
                    $objThemes->id,
                    $strToken,
                    sprintf('%s (%s)', $GLOBALS['TL_LANG']['MSC']['om_backend']['layouts'], $theme->name),
                    $this->getIcon('themeLayout')
                );
                $strToolbar .= sprintf('<a class="button" href="%scontao?do=themes&amp;table=tl_image_size&amp;id=%s&amp;rt=%s" title="%s">%s</a>',
                    $strEntryPoint,
                    $objThemes->id,
                    $strToken,
                    sprintf('%s (%s)', $GLOBALS['TL_LANG']['MSC']['om_backend']['image_size'], $theme->name),
                    $this->getIcon('themeImageSize')
                );
            }
        }

        // generate save buttons
        if (strpos($strContent, 'class="tl_submit_container"') !== false && strpos($strContent, 'name="save"') !== FALSE)
        {
            $strSave = '';
            $arrButtons = ['save', 'saveNclose', 'saveNcreate', 'saveNduplicate', 'saveNback'];

            foreach ($arrButtons as $button)
            {
                if (strpos($strContent, 'name="'.$button.'"') !== FALSE)
                {
                    $strSave .= sprintf('<a class="button" onclick="document.getElementById(\'%s\').click(); return false;" title="%s">%s</a>',
                        $button,
                        $GLOBALS['TL_LANG']['MSC'][$button],
                        $this->getIcon($button)
                    );
                }
            }
            $strToolbar .= (strlen($strSave)) ?  '<div class="separator"></div>'.$strSave : '';
        }

        // add edit multiple
        if (strpos($strContent, 'class="header_edit_all"') !== false)
        {
            // get parameter
            $id    = (strlen(\Input::get('id'))) ? '&amp;id'.\Input::get('id') : '';
            $table = (strlen(\Input::get('table'))) ? '&amp;table='.\Input::get('table') : '';

            // add separator and button
            $strToolbar .= '<div class="separator"></div>';
            $strToolbar .= sprintf('<a class="button" href="%scontao?do=%s&amp;act=select&amp;rt=%s" title="%s">%s</a>',
                $strEntryPoint,
                \Input::get('do').$table.$id,
                $strToken,
                $GLOBALS['TL_LANG']['om_backend']['stylesheets'].$objThemes->name,
                $this->getIcon('editMultiple')
            );
        }

        // edit multiple buttons
        if (strpos($strContent, 'class="tl_submit_container"') !== false && strpos($strContent, 'name="edit"') !== false)
        {
            // variables
            $arrButtons = [];

            // html button names
            $arrButtonNames = array('delete', 'cut', 'copy', 'override', 'edit', 'alias');

            // check for buttons
            foreach ($arrButtonNames as $buttonName)
            {
                // button save
                if (strpos($strContent, 'name="'.$buttonName.'"') !== false)
                {
                    $arrButtons[] = $buttonName;
                }
            }

            // add alls buttons and separator
            if (count($arrButtons) > 0)
            {
                // add separator
                $strToolbar .= '<div class="separator"></div>';

                // add buttons
                foreach ($arrButtons as $button)
                {
                    $strToolbar .= sprintf('<a class="button" onclick="document.getElementById(\'%s\').click(); return false;" title="%s">%s</a>',
                        $button,
                        $GLOBALS['TL_LANG']['om_backend']['button_'.$button],
                        $this->getIcon('multiple'.$button)
                    );
                }
            }
        }

        // create template button
        if (\Input::get('do') == 'tpl_editor' && \Input::get('key') == 'new_tpl')
        {
            // add separator
            $strToolbar .= '<div class="separator"></div>';

            // add button
            $strToolbar .= sprintf('<a class="button" onclick="document.getElementById(\'create\').click(); return false;" title="%s">%s</a>',
                $GLOBALS['TL_LANG']['om_backend']['button_'],
                $this->getIcon('save')
            );
        }

        // close container
        $strToolbar .= '</div>';

        return $strToolbar;
    }


    /**
     * @param $strIconName
     *
     * @return string
     */
    protected function getIcon($strIconName)
    {
        switch ($strIconName)
        {
            case 'idSearch':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="7.5"/><line x1="21" y1="21" x2="15.8" y2="15.8"/></svg>';

            case 'installTool':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="8" rx="2" ry="2" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="6" y1="6" x2="6" y2="6" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="6" y1="18" x2="6" y2="18" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'newTemplate':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><polyline points="14 2 14 8 20 8" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" y1="18" x2="12" y2="12" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="9" y1="15" x2="15" y2="15" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'syncFiles':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/></svg>';

            case 'themeLayout':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>';

            case 'themeImageSize':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>';

            case 'themeModule':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" fill="none" stroke="#91979c" stroke-miterlimit="10" stroke-width="2"/></svg>';

            case 'themeStylesheet':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'editMultiple':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"/><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/></svg>';

            case 'save':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><polyline points="17 21 17 13 7 13 7 21" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><polyline points="7 3 7 8 15 8" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'saveNcreate':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="12" y1="9" x2="12" y2="15" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="9" y1="12" x2="15" y2="12" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'saveNclose':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="9" y1="9" x2="15" y2="15" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="9" y1="15" x2="15" y2="9" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'saveNduplicate':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><rect x="7" y="7" width="6" height="6" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><rect x="11" y="11" width="6" height="6" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'saveNback':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><line x1="9" y1="12" x2="15" y2="12" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><polyline points="12 15 9 12 12 9" fill="none" stroke="#91979c" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>';

            case 'multipledelete':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>';

            case 'multiplecut':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>';

            case 'multiplecopy':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>';

            case 'multipleoverride':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="14 2 18 6 7 17 3 17 3 13 14 2"></polygon><line x1="3" y1="22" x2="21" y2="22"></line></svg>';

            case 'multipleedit':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon></svg>';

            case 'multiplealias':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#91979c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="6" x2="12" y2="18"/><line x1="3" y1="9" x2="8" y2="9"/><line x1="3" y1="13" x2="8" y2="13"/><line x1="16" y1="9" x2="21" y2="9"/><line x1="16" y1="13" x2="21" y2="13"/></svg>';
        }

        return '';
    }
}
