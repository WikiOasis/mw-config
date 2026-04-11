<?php
header("Content-Type: text/plain");
header("Cache-Control: max-age=86400");

$currentDomain = $_SERVER['HTTP_HOST'];

$robotsFilePath = __DIR__ . '/robots.txt';

if (file_exists($robotsFilePath)) {
    $robotsContent = file_get_contents($robotsFilePath);
    $robotsContent .= "\nSitemap: https://{$currentDomain}/sitemap.xml";
    echo $robotsContent;
} else {
    echo "File not found";
}
?>
