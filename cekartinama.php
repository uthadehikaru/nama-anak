<?php
use Symfony\Component\BrowserKit\HttpBrowser;

require 'vendor/autoload.php';
foreach (range('A', 'Z') as $alphabet){
    $httpClient = new HttpBrowser();
    $url = 'https://cekartinama.com/berdasarkan-huruf-depan-'.$alphabet.'.html';
    $count = 1;
    $data = [];
    while($url){
        echo $url . PHP_EOL;
        $response = $httpClient->request('GET', $url);
        $response->filter('.table_ind table tr')->each(function ($node) use (&$data) {
            $row = [];
            $node->filter('td')->each(function ($child) use (&$row) {
                $row[] = $child->text();
            });
            if(count($row)>1)
                $data[] = $row;
        });
        
        $next = null;
        $response->filter('.pagination .next a')->each(function($node) use (&$next) {
            if($next==null)
                $next = $node->link()->getUri();
        });
        $url = $next;
        $count++;
    }
    echo "retrieve " . count($data) .' from '. $count . PHP_EOL;

    $folder = 'results\cekartinama.com';
    if(!file_exists($folder))
        mkdir($folder);

    $fp = fopen($folder.'\nama_'.$alphabet.'.csv', 'w'); 
    
    // Loop through file pointer and a line 
    foreach ($data as $field) { 
        fputcsv($fp, $field); 
    } 
    
    fclose($fp); 
}