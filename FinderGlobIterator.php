<?php

use Hoa\File\Finder;
use Hoa\Iterator;

/**
 * This class implement a glob pattern using RegexIterator
 *
 * @copyright  Copyright © 2007-2016 Hoa community
 * @license    New BSD License
 */
class FinderGlobIterator extends Finder
{
    /**
     * Get the iterator.
     *
     * @return  \Traversable
     */
    public function getIterator()
    {
        $_iterator = new Iterator\Append();
        $types     = $this->getTypes();

        if (!empty($types)) {
            $this->_filters[] = function (\SplFileInfo $current) use ($types) {
                return in_array($current->getType(), $types);
            };
        }

        $maxDepth    = $this->getMaxDepth();
        $splFileInfo = $this->getSplFileInfo();

        $collection  = $this->getPaths();
        $paths       = [];
        foreach ($collection as $path) {
            $iterator = new Iterator\CallbackFilter(
                new Iterator\Glob($path),
                function($current) {
                    return $current->isDir();
                }
            );
            foreach ($iterator as $path => $fileInfo) {
                $paths[] = $path;
            }
        }
        $paths = array_unique($paths);

        foreach ($paths as $path) {
            if (1 == $maxDepth) {
                $iterator = new Iterator\IteratorIterator(
                    new Iterator\Recursive\Directory(
                        $path,
                        $this->getFlags(),
                        $splFileInfo
                    ),
                    $this->getFirst()
                );
            } else {
                $iterator = new Iterator\Recursive\Iterator(
                    new Iterator\Recursive\Directory(
                        $path,
                        $this->getFlags(),
                        $splFileInfo
                    ),
                    $this->getFirst()
                );

                if (1 < $maxDepth) {
                    $iterator->setMaxDepth($maxDepth - 1);
                }
            }

            $_iterator->append($iterator);
        }

        foreach ($this->getFilters() as $filter) {
            $_iterator = new Iterator\CallbackFilter(
                $_iterator,
                $filter
            );
        }

        $sorts = $this->getSorts();

        if (empty($sorts)) {
            return $_iterator;
        }

        $array = iterator_to_array($_iterator);

        foreach ($sorts as $sort) {
            uasort($array, $sort);
        }

        return new Iterator\Map($array);
    }
}
