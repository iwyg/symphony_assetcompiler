<?php

$baseDir   = dirname(__FILE__).'/lib';
$vendorDir = dirname($baseDir).'/vendor';

return array(
    'Thapp\\Symext\\Assets\\Lib\\Compiler' => $baseDir.DIRECTORY_SEPARATOR.'Compiler.php',
    'Thapp\\Symext\\Assets\\Lib\\Cache' => $baseDir.DIRECTORY_SEPARATOR.'Cache.php',
    'MatthiasMullie\\Minify\\Minify' => $vendorDir.DIRECTORY_SEPARATOR.'matthiasmullie'.DIRECTORY_SEPARATOR.'minify'.DIRECTORY_SEPARATOR.'Minify.php',
    'MatthiasMullie\\Minify\\CSS' => $vendorDir.DIRECTORY_SEPARATOR.'matthiasmullie'.DIRECTORY_SEPARATOR.'minify'.DIRECTORY_SEPARATOR.'CSS.php',
    'MatthiasMullie\\Minify\\JS' => $vendorDir.DIRECTORY_SEPARATOR.'matthiasmullie'.DIRECTORY_SEPARATOR.'minify'.DIRECTORY_SEPARATOR.'JS.php',
    'JsMin\\Minify' => $vendorDir.DIRECTORY_SEPARATOR.'nick4fake'.DIRECTORY_SEPARATOR.'jsmin'.DIRECTORY_SEPARATOR.'JsMin'.DIRECTORY_SEPARATOR.'Minify.php',
    'JsMin\\Exception\\UnterminatedComment' => $vendorDir.DIRECTORY_SEPARATOR.'nick4fake'.DIRECTORY_SEPARATOR.'jsmin'.DIRECTORY_SEPARATOR.'JsMin'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnterminatedComment.php',
    'JsMin\\Exception\\UnterminatedRegExp' => $vendorDir.DIRECTORY_SEPARATOR.'nick4fake'.DIRECTORY_SEPARATOR.'jsmin'.DIRECTORY_SEPARATOR.'JsMin'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnterminatedRegExp.php',
    'JsMin\\Exception\\UnterminatedString' => $vendorDir.DIRECTORY_SEPARATOR.'nick4fake'.DIRECTORY_SEPARATOR.'jsmin'.DIRECTORY_SEPARATOR.'JsMin'.DIRECTORY_SEPARATOR.'Exception'.DIRECTORY_SEPARATOR.'UnterminatedString.php',
);
