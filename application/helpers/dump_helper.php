<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump($var, $label = 'Dump')
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
        $output = <<<EOT
<pre style="background-color:#042027;border:none;color:#15a2e6;border-radius:0;padding:15px;">$label => $output</pre>
EOT;
        echo $output;
    }
}

if (!function_exists('dd')) {
    function dd($var, $label = 'Dump&Die')
    {
        dump($var, $label);
        die();
    }
}