<?php

use Hoa\File\Finder;
use Hoa\Iterator;

/**
 * This class implement a glob pattern using glob function
 *
 * @copyright  Copyright © 2007-2016 Hoa community
 * @license    New BSD License
 */
class FinderGlob extends Finder
{
    /**
     * Select a directory to scan.
     *
     * @param   string  $path    Path.
     * @return  \Hoa\File\Finder
     */
    public function in($path)
    {
        if (!is_array($path)) {
            $path = [$path];
        }

        foreach ($path as $p) {
            if( 1 === preg_match('/\*|\?|\[([^\]]+)\]|\{([^}]+)\}/', $p) ) {
                $this->_paths = array_merge(
                    $this->_paths,
                    glob($p, GLOB_ONLYDIR|GLOB_BRACE)
                );
                var_dump($this->_paths);
            } else {
                $this->_paths[] = $p;
            }
        }

        return $this;
    }
}
