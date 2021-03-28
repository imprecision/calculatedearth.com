<?php

/**
 * Build script for creation of website
 * 
 * Please note this static build process is built over what was originally a 
 * dynamic website built back in 2007. Yes it's an ugly hack. Yes the 2007 
 * code is, well, 2007 code. But this is a simple way to get this site over to
 * a static deployment.
 * 
 * Usage:
 * 
 * In this folder fire up a basic webserver, e.g. from the command line:
 * php -S 127.0.0.1:8080
 * 
 * To trigger the build process, from your browser go here:
 * http://120.0.0.1/build.php
 * 
 * Notes:
 * 
 * This script is for *nix based systems, e.g. the "rm -rf..." bit. Be warned!
 * 
 */

define('CA_URL_ROOT',   '/'); // The folder from URL root you want to host the site in. e.g. Base root would be "/".
define('CA_RES_SOURCE', 'src-static/res/sections/'); // Where the source static world images are held
define('CA_RES_TARGET', 'res/sections/'); // Where to put the static world images within the website structure

function l($m) {
    $l = microtime(true) . "\t" . $m;
    print $l . "\n";
    error_log($l);
}

require "src/lib_core.php";

header("Content-type: text/plain");

l("Starting build process.");

l("Runtime URL directory from root: " . CA_URL_ROOT);

// Clear the build folder
exec("rm -rf docs/*");
l("Build directory cleared.");

// Copy static content
exec("cp -r src-static/* docs/");
l("Static resources copied.");

// Create the site's core HTML files
$files = [
    "src/tmpl_about.php" => "docs/about/index.html",
    "src/tmpl_links.php" => "docs/links/index.html",
    "src/tmpl_index.php" => "docs/index.html",
];

foreach ($files as $source => $target) {
    ob_start();
    require "src/lib.php";
    require "src/hdr.php";
    require $source;
    require "src/ftr.php";
    $html = ob_get_clean();

    $target_info = pathinfo($target);

    if (!file_exists($target_info["dirname"])) {
        mkdir($target_info["dirname"], 644, true);
    }
    file_put_contents($target, $html);

    l($target);
}

// Create the world-overview pages
$files = [
    "eu" => "docs/earth/eu/index.html",
    "us" => "docs/earth/us/index.html",
    "ea" => "docs/earth/ea/index.html",
];

foreach ($files as $source => $target) {
    $_SERVER['QUERY_STRING'] = $source;

    ob_start();
    require "src/lib.php";
    require "src/hdr.php";
    require "src/tmpl_index.php";
    require "src/ftr.php";
    $html = ob_get_clean();

    $target_info = pathinfo($target);

    if (!file_exists($target_info["dirname"])) {
        mkdir($target_info["dirname"], 644, true);
    }
    file_put_contents($target, $html);

    l($target);
}

// Create all world grid areas pages
for ($r_y = 0; $r_y < 5400; $r_y = $r_y + 600) {
    for ($r_x = 0; $r_x < 10800; $r_x = $r_x + 600) {
        $_SERVER['QUERY_STRING'] = $r_x . "x" . $r_y;
        $target = "docs/earth/" . $r_x . "x" . $r_y . "/index.html";

        ob_start();
        require "src/lib.php";
        require "src/hdr.php";
        require "src/tmpl_index.php";
        require "src/ftr.php";
        $html = ob_get_clean();
    
        $target_info = pathinfo($target);
    
        if (!file_exists($target_info["dirname"])) {
            mkdir($target_info["dirname"], 644, true);
        }
        file_put_contents($target, $html);

        l($target);
    }
}

l("All done!");
