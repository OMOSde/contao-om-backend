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
 * Use
 */
use Contao\Controller;


/**
 * Class ModuleMarkdownViewer
 *
 * @copyright René Fehrmann
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class ModuleMarkdownViewer extends \BackendModule
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_markdown_viewer';


    /**
     * Generate module
     */
    protected function compile()
    {
        $arrMarkdownFiles = [];

        // get markdown files in root dir
        $arrFiles = scandir(TL_ROOT);
        foreach ($arrFiles as $file)
        {
            $strFile = TL_ROOT . '/' . $file;
            if (is_file($strFile))
            {
                $arrPathinfo = pathinfo($strFile);
                if ($arrPathinfo['extension'] == 'md')
                {
                    $arrMarkdownFiles[] = $file;
                }
            }
        }

        // no files found
        if (!is_array($arrMarkdownFiles) || empty($arrMarkdownFiles))
        {
            $this->Template->error = 'Keine Markdown-Dateien gefunden!';

            return;
        }

        // save edited file
        if (\Input::post('FORM_SUBMIT') == 'tl_markdown_edit')
        {
            \File::putContent(\Input::get('id'), html_entity_decode(\Input::post('markdown')));
            \Controller::redirect(str_replace(['&act=source', 'id='], ['', 'tab='], \Environment::get('request')));
        }

        // show editor
        if (\Input::get('act') === 'source' && substr(\Input::get('id'), -3, 3) === '.md')
        {
            $objTemplate = new \BackendTemplate('be_ace');
            $objTemplate->selector = 'markdown';
            $objTemplate->type = 'md';

            $this->Template->markdown = file_get_contents(TL_ROOT . '/' . \Input::get('id'));
            $this->Template->editor = $objTemplate->parse();
            $this->Template->filename = \Input::get('id');
            $this->Template->action = ampersand(\Environment::get('request'));
            $this->Template->back = $this->getReferer(true);

            return;
        }

        // generate tabs from found .md files
        $strManager = '<div id="manager"><ul>';
        foreach ($arrMarkdownFiles as $intKey => $strMarkdownFile)
        {
            // link
            $strHref = sprintf('contao/main.php?do=markdown_view&amp;tab=%s&amp;rt=%s"', $strMarkdownFile, REQUEST_TOKEN);

            // add class
            if (!\Input::get('tab'))
            {
                $strClass = ($intKey == 0) ? ' class="current"' : '';
            }
            else
            {
                $strClass = (\Input::get('tab') == $strMarkdownFile) ? ' class="current"' : '';
            }

            // list item
            $strManager .= sprintf('<li%s style="margin-right:4px;"><a href="%s" title="%s">%s</a></li>',
                $strClass,
                $strHref,
                $strMarkdownFile,
                $strMarkdownFile);
        }
        $strManager .= '</ul></div>';

        // parse markdown to html
        $objParsedown = new \Parsedown();
        $strFile = (\Input::get('tab')) ?: $arrMarkdownFiles[0];
        $strHtml = $objParsedown->text(file_get_contents(TL_ROOT . '/' . $strFile));


        // set template vars
        $this->Template->manager = $strManager;
        $this->Template->html = Controller::replaceInsertTags($strHtml, false);
        $this->Template->filename = $strFile;
        $this->Template->edit = (\Input::get('act') === 'source');
        $this->Template->link = sprintf('contao/main.php?do=markdown_view&amp;act=source&amp;id=%s&amp;rt=%s', $strFile, REQUEST_TOKEN);
    }
}
