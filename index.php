<?php
// Front controller for Hostinger PHP hosting - serves Astro static build from dist/
// Upload layout in public_html/: index.php, .htaccess, dist/{index.html,packages/index.html,_astro/*,images/*}

$base = __DIR__ . '/dist';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if (!is_string($uri) || $uri === '') {
    $uri = '/';
}
$path = urldecode($uri);
if (strpos($path, '..') !== false || strpos($path, "\0") !== false) {
    http_response_code(400);
    exit('Bad request');
}
$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

$candidates = [];
if ($path === '/') {
    $candidates[] = $base . '/index.html';
    $candidates[] = __DIR__ . '/index.html';
} else {
    $rel = ltrim($path, '/');
    $candidates[] = $base . '/' . $rel . '.html';       // e.g. /about -> dist/about.html
    $candidates[] = $base . '/' . $rel . '/index.html'; // e.g. /packages -> dist/packages/index.html
    // Flattened-upload fallback (dist contents copied to web root)
    $candidates[] = __DIR__ . '/' . $rel . '.html';
    $candidates[] = __DIR__ . '/' . $rel . '/index.html';
}

foreach ($candidates as $file) {
    if (is_file($file)) {
        readfile($file);
        exit;
    }
}

http_response_code(404);
echo 'Page not found.';
