<?php

namespace App\Helpers\Routes;

class RouteHelper
{
    public static function includeRouteFiles(string $folder)
    {
        // iterate through the v1 folder recursively
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /** @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator($dirIterator);
        // require the file in each iteration

        // the valid() method checks if current position is valid eg 
        // there is a valid file or directory at the current position
        while ($it->valid()) {
            if (
                // isDot to make sure it is not current or parent directory
                !$it->isDot()
                && $it->isFile()
                && $it->isReadable()
                && $it->current()->getExtension() === 'php'
            ) {
                require $it->key();
                // require $it->current()->getPathname();
            }
            $it->next();
        }
    }
}
