<?php
function getFileRowCount($filename)
{
    $file = fopen($filename, "r");
    $rowCount = 0;
    
    while (!feof($file)) {
        fgets($file);
        $rowCount++;

    }

    fclose($file);

    return $rowCount;
}
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$fullUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if (isset($fullUrl)) {
    $parsedUrl = parse_url($fullUrl);
    $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] : '';
    $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
    $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
    $baseUrl = $scheme . "://" . $host . $path;
    $urlAsli = str_replace("1.php", "", $baseUrl);
    $robotsTxt = "User-agent: *" . PHP_EOL;
    $robotsTxt .= "Allow: /" . PHP_EOL;
    $robotsTxt .= "Sitemap: " . $urlAsli . "sitemap-1.xml" . PHP_EOL;
    file_put_contents('robots.txt', $robotsTxt);
    $judulFile = "note.txt";
    $jumlahBaris = getFileRowCount($judulFile);
    $sitemapFile = fopen("sitemap-1.xml", "w");
    fwrite($sitemapFile, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
    fwrite($sitemapFile, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL);
    $fileLines = file($judulFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($fileLines as $index => $judul) {
        $sitemapLink = $urlAsli . '?id_ID=' . urlencode($judul);
//		$sitemapLink ="https://selaimanis.top/amp/" . '?id_ID=' . urlencode($judul);
        fwrite($sitemapFile, '  <url>' . PHP_EOL);
        fwrite($sitemapFile, '    <loc>' . $sitemapLink . '</loc>' . PHP_EOL);
 // Menambahkan <lastmod>
        date_default_timezone_set('Asia/Jakarta');
        $currentTime = date('Y-m-d\TH:i:sP');
        fwrite($sitemapFile, '    <lastmod>' . $currentTime . '</lastmod>' . PHP_EOL);
        fwrite($sitemapFile, '    <priority>1.00</priority>' . PHP_EOL);
        
       
        
        fwrite($sitemapFile, '  </url>' . PHP_EOL);
    }
    fwrite($sitemapFile, '</urlset>' . PHP_EOL);
    fclose($sitemapFile);   
    echo "<h1>done</h1>";
} else {
    echo "URL saat ini tidak didefinisikan.";
}
?>
