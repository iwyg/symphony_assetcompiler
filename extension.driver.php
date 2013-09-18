<?php

/**
 * This File is part of the extensions\assets package
 *
 * (c) Thomas Appel <mail@thomas-appel.com>
 *
 * For full copyright and license information, please refer to the LICENSE file
 * that was distributed with this package.
 */

require_once __DIR__.'/autoload.php';

use Thapp\Symext\Assets\Lib\Cache;
use Thapp\Symext\Assets\Lib\Compiler;

/**
 * @class Extension_Assets
 * @package extensions\assets
 * @version $Id$
 */
class Extension_Assets extends Extension
{
    /**
     * getSubscribedDelegates
     *
     * @access public
     */
    public function getSubscribedDelegates()
    {
        return array(
            array(
                'page' => '/frontend/',
                'delegate' => 'FrontendOutputPostGenerate',
                'callback' => 'getAssetCompilerDispatcher'
            )
        );
    }

    /**
     * getAssetCompilerDispatcher
     *
     * @access public
     */
    public function getAssetCompilerDispatcher($context)
    {

        if (0 !== stripos($context['output'], '<!DOCTYPE')) {
            return;
        }

        $compiler = new Compiler($context['output'], new Cache, DOCROOT, URL);
        $compiler->setDestination(DOCROOT.DIRECTORY_SEPARATOR.'workspace'.DIRECTORY_SEPARATOR.'dist');

        if ($compiler->check('js')->isFresh()) {
            $compiler->compile('js', true);
        } else {
            $compiler->replaceJs('js');
        }

        if ($compiler->check('css')->isFresh()) {
            $compiler->compile('css', true);
        } else {
            $compiler->replaceCss('css');
        }

        $context['output'] = $compiler->getHtml();
    }
}

