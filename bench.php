<?php

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/FinderGlob.php';
require_once __DIR__.'/FinderGlobIterator.php';

use Hoa\File\Finder;

$toBench = function(Finder $finder) use ($argv) {
    $i = 0;
    $finder->in($argv[1])->maxDepth(5);
    $it = $finder->getIterator();
    foreach( $it as $path ) {
        $i++;
    }
    var_dump($i);
};
$bench = new Hoa\Bench;

if( is_dir($argv[1]) ) {
    $bench->noglob->start();
    call_user_func($toBench, new FinderNoGlob);
    $bench->noglob->stop();
}

$bench->glob->start();
call_user_func($toBench, new FinderGlob);
$bench->glob->stop();
usleep(1000);
$bench->iterator->start();
call_user_func($toBench, new FinderGlobIterator);
$bench->iterator->stop();

// Print statistics.
echo $bench;
