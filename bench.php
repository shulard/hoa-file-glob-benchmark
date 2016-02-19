<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/FinderGlob.php';
require_once __DIR__.'/FinderGlobNoDetect.php';
require_once __DIR__.'/FinderNoGlob.php';
require_once __DIR__.'/FinderGlobIterator.php';
require_once __DIR__.'/FinderGlobIteratorNoDetect.php';

use Hoa\File\Finder;

$toBench = function(Finder $finder, $onlyDir = false) use ($argv) {
    $i = 0;
    for( $j = 1; $j < count($argv); $j++ ) {
        if( !$onlyDir || is_dir($argv[$j]) ) {
            $finder->in($argv[$j])->maxDepth(5);
        }
    }
    $it = $finder->getIterator();
    foreach( $it as $path ) {
        $i++;
    }
    var_dump($i);
};
$bench = new Hoa\Bench;

$bench->noglob->start();
call_user_func($toBench, new FinderNoGlob, true);
$bench->noglob->stop();
usleep(1000);
$bench->glob->start();
call_user_func($toBench, new FinderGlob);
$bench->glob->stop();
usleep(1000);
$bench->iterator->start();
call_user_func($toBench, new FinderGlobIterator);
$bench->iterator->stop();
usleep(1000);
$bench->globnodetect->start();
call_user_func($toBench, new FinderGlobNoDetect);
$bench->globnodetect->stop();
usleep(1000);
$bench->iteratornodetect->start();
call_user_func($toBench, new FinderGlobIteratorNoDetect);
$bench->iteratornodetect->stop();

// Print statistics.
echo $bench;
