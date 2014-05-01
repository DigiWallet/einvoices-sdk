<?php
namespace Yepster;
 
/**
 * Autoloader
 * Better use the global autoloader of your framework (e.g. Composer)
 * If that doesn't work, include it with require_once /path/to/autoload.php
 *
 * @author  Yellow Melon B.V.
 * @license BSD License
 * @version 1.0
 */

function autoload($name)
{
    // Check if the name starts with Yepster
    if (\substr_compare($name, "Yepster\\", 0, 8) !== 0) return;

    // Take the "Dropbox\" prefix off.
    $stem = \substr($name, 8);

    // Convert "\" and "_" to path separators.
    $pathified_stem = \str_replace(array("\\", "_"), '/', $stem);

    $path = __DIR__ . "/" . $pathified_stem . ".php";
    if (\is_file($path)) {
        require_once $path;
    }
}

\spl_autoload_register('Yepster\autoload');
