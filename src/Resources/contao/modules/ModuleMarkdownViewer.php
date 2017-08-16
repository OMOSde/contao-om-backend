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
 * Class ModuleMarkdownViewer
 *
 * @copyright René Fehrmann
 * @author    René Fehrmann <rene.fehrmann@omos.de>
 */
class ModuleMarkdownViewer extends \BackendModule
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_markdown_viewer';


    /**
     * Generate module
     */
    protected function compile()
    {
        // get markdown files in root dir
        $arrFiles = scandir(TL_ROOT);
        foreach ($arrFiles as $file)
        {
            $strFile = TL_ROOT.'/'.$file;
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
        if (!is_array($arrMarkdownFiles))
        {
            $this->Template->error = 'Keine Markdown-Dateien gefunden!';
            return;
        }

        // generate tabs from found .md files
        $strManager = '<div id="manager"><ul>';
        foreach ($arrMarkdownFiles as $intKey=>$strMarkdownFile)
        {
            // link
            $strHref = sprintf('%scontao?do=markdown_view&tab=%s',
                (strpos(\Environment::get('request'), 'app_dev.php') !== false) ? 'app_dev.php/' : '',
                $strMarkdownFile
            );

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
                $strMarkdownFile
            );
        }
        $strManager .= '</ul></div>';

        // parse markdown to html
        $objParsedown = new \Parsedown();
        $strFile = (\Input::get('tab')) ?: $arrMarkdownFiles[0];
        $strHtml = $objParsedown->text(file_get_contents(TL_ROOT.'/'.$strFile));

        // set template vars
        $this->Template->manager = $strManager;
        $this->Template->html    = $strHtml;
    }
}
