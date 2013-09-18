<?php

/**
 * This File is part of the extensions\assets\lib package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

namespace Thapp\Symext\Assets\Lib;

use \DOMXPath;
use \DOMElement;
use \DOMDocument;
use MatthiasMullie\Minify\JS;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\Minify;
use JsMin\Minify as JsMinify;

/**
 * @class compiler
 * @package extensions\assets\lib
 * @version $Id$
 */
class Compiler
{
    private $url;
    private $root;
    private $scripts;
    private $scriptNodes = array();

    /**
     * __construct
     *
     * @param mixed $html
     *
     * @access public
     * @return mixed
     */
    public function __construct($html, Cache $cache, $root = null, $url)
    {
        $this->root = $root;
        $this->url  = $url;
        $this->cache = $cache;
        $this->parse($html);
    }

    /**
     * parse
     *
     * @param mixed $html
     *
     * @access private
     * @return mixed
     */
    private function parse($html)
    {
        $this->dom = new DOMDocument;
        $this->dom->loadHTML($html);
        $this->xpath = new DOMXpath($this->dom);
    }

    /**
     * checkt
     *
     * @param mixed $type
     *
     * @access public
     * @return mixed
     */
    public function check($type = 'js')
    {
        switch($type) {
            case 'js':
                return $this->cache->check($this->getScripts(), $type);
            case 'css':
                return $this->cache->check($this->getStylesheets(), $type);
            default:
                throw new \InvalidArgumentException('Unreckognized type');
        }
    }

    /**
     * setDestination
     *
     * @param mixed $dest
     *
     * @access public
     * @return void
     */
    public function setDestination($dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0775, true);
        }
        $this->dest = $dest;
        $this->cache->setDestinationPath($this->dest);
    }

    /**
     * findCompiledFile
     *
     * @param string $type
     *
     * @access private
     * @return void
     */
    private function findCompiledFile($type = 'js')
    {
        return file_exists($file = $this->cache->getDestFileName($type)) ? $file : false;
    }

    /**
     * compile
     *
     * @param string $type
     * @param mixed $replaceSource
     *
     * @access public
     * @return mixed
     */
    public function compile($type = 'js')
    {
        switch ($type) {
            case 'js':
                $this->compileJs($file = $this->cache->getDestFileName($type));
                break;
            case 'css':
                $this->compileCss($file = $this->cache->getDestFileName($type));
                break;
        }

        $this->replaceDomScripts($type, $file);
    }

    /**
     * getFileUlr
     *
     * @param mixed $file
     *
     * @access private
     * @return mixed
     */
    private function getFileUlr($file)
    {
        return $this->url .'/' . ltrim(substr(str_replace(DIRECTORY_SEPARATOR, '/', $file), strlen($this->root)), '/');
    }

    /**
     * replaceJs
     *
     * @access public
     * @return void
     */
    public function replaceJs()
    {
        return $this->replaceDomScripts('js', $this->getFileUlr($this->cache->getDestFileName('js')));
    }

    /**
     * getHtml
     *
     *
     * @access public
     * @return mixed
     */
    public function getHtml()
    {
        return $this->dom->saveHTML();
    }

    /**
     * replaceCss
     *
     *
     * @access public
     * @return void
     */
    public function replaceCss()
    {
        return $this->replaceDomScripts('css', $this->getFileUlr($this->cache->getDestFileName('css')));
    }

    /**
     * replaceDomScripts
     *
     * @param mixed $type
     * @param mixed $file
     *
     * @access public
     * @return void
     */
    public function replaceDomScripts($type, $file)
    {
        $nodes = $this->scriptNodes[$type];
        if (0 === $nodes->length) {
            return;
        }

        if ('js' === $type) {
            $script = $this->dom->createElement('script');
            $script->setAttribute('src', $file);
        }

        if ('css' === $type) {
            $script = $this->dom->createElement('link');
            $script->setAttribute('href', $file);
            $script->setAttribute('rel', 'stylesheet');
        }

        $lastNode = $nodes->item($nodes->length - 1);
        $lastNode->parentNode->insertBefore($script, $lastNode);
        foreach ($nodes as $node) {
            $node->parentNode->removeChild($node);
        }
    }

    /**
     * compileCss
     *
     * @param mixed $dest
     *
     * @access private
     * @return mixed
     */
    private function compileCss($dest)
    {
        $compiler = new CSS;
        foreach ($this->getStylesheets() as $file) {
            $compiler->add($file);
        }
        $compiler->minify($dest, CSS::ALL);
    }

    /**
     * compileJs
     *
     * @param mixed $dest
     *
     * @access private
     * @return mixed
     */
    private function compileJs($dest)
    {
        $contents = array();
        foreach ($this->getScripts() as $file) {
            $content[] = file_get_contents($file);
        }
        $parser = new JsMinify(implode("\n", $content));
        file_put_contents($dest, $parser->min(), LOCK_EX);
    }

    /**
     * getScripts
     *
     * @access private
     * @return array
     */
    private function getScripts()
    {
        if (is_null($this->scripts)) {
            $this->scripts = $this->getAssetSource("//script[@data-compile = 'true']", 'src', 'js');
        }
        return $this->scripts;
    }

    /**
     * getStylesheets
     *
     * @param mixed $node
     *
     * @access private
     * @return array
     */
    private function getStylesheets()
    {
        if (is_null($this->css)) {
            $this->css = $this->getAssetSource("//link[@rel = 'stylesheet' and @data-compile = 'true']", 'href', 'css');
        }

        return $this->css;
    }

    /**
     * getAssetSource
     *
     * @param mixed $query
     * @param mixed $attr
     *
     * @access private
     * @return mixed
     */
    private function getAssetSource($query, $attr, $type = 'js')
    {
        $assets = array();
        $this->scriptNodes[$type] = $nodes = $this->xpath->query($query);
        foreach ($nodes as $file) {
            $assets[] = $this->getFilePath($this->getScriptSource($file, $attr));
        }
        return $assets;
    }

    /**
     * getFilePath
     *
     * @param mixed $file
     *
     * @access private
     * @return mixed
     */
    private function getFilePath($file)
    {
        return $this->root.DIRECTORY_SEPARATOR.str_replace('\\/', DIRECTORY_SEPARATOR, ltrim($file, '/'));
    }

    /**
     * getScriptSource
     *
     * @param DOMElement $script
     *
     * @access private
     * @return mixed
     */
    private function getScriptSource(DOMElement $script, $attr = 'src')
    {
        $src = $script->getAttribute($attr);

        if (0 !== strpos($src, 'http')) {
            return $src;
        }

        return substr($src, strlen($this->url));
    }
}
