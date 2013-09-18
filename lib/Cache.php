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

/**
 * @class Cache
 * @package extensions\assets\lib
 * @version $Id$
 */
class Cache
{
    private $current;

    private $files;

    private $dest;

    private $path;

    public function __construct($path = '/')
    {
        $this->path = $path;
    }

    /**
     * getDestFileName
     *
     * @param string $type
     *
     * @access public
     * @return mixed
     */
    public function getDestFileName($type = 'js')
    {
        if (!isset($this->dest[$type])) {
            $this->dest[$type] = $this->path.DIRECTORY_SEPARATOR.'application-'.hash('md5', implode(';', $this->files[$type])).'.'.$type;
        }

        return $this->dest[$type];
    }

    public function check(array $files, $id = 'js')
    {
        $this->files[$id] = $files;
        $this->reference[$id] = file_exists($file = $this->getDestFileName($id)) ? $file : false;
        $this->current = $id;
        return $this;
    }

    public function setDestinationPath($path)
    {
        $this->path = $path;
    }



    public function isFresh()
    {
        if (!$ref = $this->reference[$this->current]) {
            return true;
        }

        $files = $this->files[$this->current];
        $refTime   = filemtime($ref);

        while(count($files)) {
            $file = array_shift($files);
            if (@filemtime($file) > $refTime) {
                return true;
            }
        }
        return false;
    }
}
