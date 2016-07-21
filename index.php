<?php
include("config.php"); //laad config
function download($url, $destination) {
    
    try {
        
        $fp = fopen($destination, "w");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, USERNAME . ":" . PASSWORD);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $resp = curl_exec($ch);
        // check CURL status
        if(curl_errno($ch))
            throw new Exception(curl_error($ch), 500);
        // check of de url geldig is
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($status_code != 200)
            throw new Exception("Response status code [" . $status_code . "].", 500);
            
    }
    
    
    catch(Exception $ex) {
        
        if ($ch != null) curl_close($ch);
        if ($fp != null) fclose($fp);
        throw new Exception('Kan bestand niet downloaden van url=[' + $url + '] naar [' + $destination + '].', 500, $ex);
        
    }
    
    if ($ch != null) curl_close($ch);
    if ($fp != null) fclose($fp);
}


download(REPO_ZIP_URL, 'cache/git.zip');
$zip = new ZipArchive;


if ($zip->open('cache/git.zip') === TRUE) {
    
    $zip->extractTo(OUTPUTPATH);
    $zip->close();
    echo 'update gelukt----';
    
} else {
    
    echo 'failed-----';
}


$za = new ZipArchive(); 
$za->open('cache/git.zip'); 
 $stat = $za->statIndex( 0 ); 
$gitname = basename( $stat['name']);
echo $gitname;
function recursiveRemove($dir) {
    
    $structure = glob(rtrim($dir, "/").'/*');
    if (is_array($structure)) {
        
        foreach($structure as $file) {
            
            if (is_dir($file)) recursiveRemove($file);
            elseif (is_file($file)) unlink($file);
        }
        
    }
    
    rmdir($dir);
}

recursiveRemove(OUTPUTPATH . "master");
rename(OUTPUTPATH . $gitname , OUTPUTPATH . "master");
if (ENABLE_LOG == 'true') {
    
    $file = 'log/log.log';
    $current = file_get_contents($file);
    $current .= "[" . date("Y-m-d") . "  " . date("h:i:sa") . "] Update in online project git, deze update is nu online op de test server (door bot) met patch id: " . $gitname . "\n";
    file_put_contents($file, $current);
    $file = 'log/ver.log';
    $current = file_get_contents($file);
    $current = $gitname;
    file_put_contents($file, $current);
}

?>
