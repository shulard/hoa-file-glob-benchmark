<?php

use Hoa\File\Finder;
use Hoa\Iterator;

/**
 * This class implement a glob pattern using RegexIterator
 *
 * @copyright  Copyright © 2007-2016 Hoa community
 * @license    New BSD License
 */
class FinderGlobIteratorNoDetect extends Finder
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
            $iterator = new Iterator\CallbackFilter(
                new Iterator\Glob(rtrim($p, '/')),
                function($current) {
                    return $current->isDir();
                }
            );
            foreach ($iterator as $p => $fileInfo) {
                $this->_paths[] = $p;
            }
        }

        return $this;
    }
}
