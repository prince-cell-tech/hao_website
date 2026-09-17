<?php
// Entry for Hostinger PHP hosting - serves Astro static build, keeps PHP available
// Upload layout expected in public_html/: index.php + dist/index.html + dist/_astro/* + dist/images/*

$local = __DIR__ . '/dist/index.html';  // subfolder layout
$flat  = __DIR__ . '/index.html';       // flattened upload fallback

if (file_exists($local)) {
    readfile($local);
} elseif (file_exists($flat)) {
    readfile($flat);
} else {
    http_response_code(500);
    echo 'Site not deployed. Upload dist/ contents.';
}
