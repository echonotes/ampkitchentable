<?php
// Cek apakah file sitemap-1.xml ada
$sitemapFile = 'sitemap-1.xml';
$hasilFile = 'hasilurl.php'; // File tempat menyimpan hasil link

if (file_exists($sitemapFile)) {
    // Muat file XML
    $xml = simplexml_load_file($sitemapFile);

    // Cek apakah file XML valid dan mengandung data URL
    if ($xml !== false) {
        // Buka file hasilurl.php untuk ditulis ulang
        $hasil = fopen($hasilFile, 'w');

        // Tuliskan awal file PHP ke dalam hasilurl.php
        fwrite($hasil, "<?php\n");
        fwrite($hasil, "echo \"<h1>Daftar Link</h1>\";\n");
        fwrite($hasil, "echo \"<ul>\";\n");

        // Loop setiap tag <url> dalam sitemap
        foreach ($xml->url as $urlElement) {
            $loc = (string)$urlElement->loc; // Ambil nilai dari elemen <loc>

            // Parse URL untuk memisahkan query string
            $parsedUrl = parse_url($loc);
            $linkText = $loc; // Inisialisasi nama link sebagai URL asli

            // Jika URL memiliki query string, kita urai query string-nya
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams); // Mengurai query string ke dalam array

                // Cek apakah ada parameter id_ID
                if (isset($queryParams['id_ID'])) {
                    $linkText = $queryParams['id_ID']; // Ganti nama link dengan nilai id_ID
                }
            }

            // Tulis link ke file hasilurl.php
            fwrite($hasil, "echo \"<li><a href='$loc'>$linkText</a></li>\";\n");
        }

        // Tuliskan penutupan <ul> ke dalam file hasilurl.php
        fwrite($hasil, "echo \"</ul>\";\n");

        // Tutup tag PHP di akhir file
        fwrite($hasil, "?>");

        // Tutup file hasilurl.php
        fclose($hasil);

        echo "<h1>done</h1>";
    } else {
        echo "Gagal memuat sitemap.";
    }
} else {
    echo "File sitemap-1.xml tidak ditemukan.";
}
?>
