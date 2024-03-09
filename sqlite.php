<?php

$db = new SQLite3('nama.db');

if(!$db) {
    echo $db->lastErrorMsg().PHP_EOL;
} else {
    echo "Open database success...\n";

    $sql =<<<EOF
        -- "data" definition

        CREATE TABLE IF NOT EXISTS "data" (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            gender TEXT,
            "type" TEXT,
            description TEXT
        );
    EOF;

    $ret = $db->exec($sql);
    if(!$ret){
    echo $db->lastErrorMsg().PHP_EOL;
    } else {
    echo "create table is success...\n";
    }

    $sql ="DELETE FROM data";

    $ret = $db->exec($sql);
    if(!$ret){
    echo $db->lastErrorMsg().PHP_EOL;
    } else {
    echo "clean table is success...\n";
    }

    foreach (range('A', 'Z') as $alphabet){
        $file = fopen('results/cekartinama.com/nama_'.$alphabet.'.csv', 'r'); 
    
        while(! feof($file))
        {
            $data = fgetcsv($file);
            $stmt = $db->prepare('INSERT INTO data(name,gender,`type`,description) VALUES(:name,:gender,:type,:desc)');
            $stmt->bindValue(':name', $data[1], SQLITE3_TEXT);
            $stmt->bindValue(':gender', $data[2], SQLITE3_TEXT);
            $stmt->bindValue(':type', $data[3], SQLITE3_TEXT);
            $stmt->bindValue(':desc', $data[4], SQLITE3_TEXT);
            
            $result = $stmt->execute();
            if(!$result){
                echo $sql. ': '.$db->lastErrorMsg();
                break;
            }
        }
        
        fclose($file); 

        echo "inserted data ".$alphabet . PHP_EOL;
    }

}