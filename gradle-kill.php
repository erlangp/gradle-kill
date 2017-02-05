<?php

/**
 * AppName: gradle-kill.php
 * Description: Force kill(stop) Gradle process (Android Studio)
 * Tags: windows, android, java, php
 * By: ErlangParasu 2017/02/06
 */
$command = 'wmic process where caption="java.exe" get commandline,processid';
$result = shell_exec($command);
$arr_contents = explode("\n", $result);

print_r($arr_contents);
echo PHP_EOL;

if ($arr_contents) {
    echo 'Java process detected' . PHP_EOL;
} else {
    echo 'No Java proccess detected' . PHP_EOL;
    exit();
}
echo PHP_EOL;

$c = null;
$p = null;
foreach ($arr_contents as $i => $line) {
    $c = strpos($line, 'CommandLine');
    if ($c !== false) {
        $p = strpos($line, 'ProcessId');
        if ($p !== false) {
            break;
        }
    }
}
echo 'c: ' . $c . PHP_EOL;
echo 'p: ' . $p . PHP_EOL;
echo PHP_EOL;

$data = null;
foreach ($arr_contents as $i => $line) {
    $found = strpos($line, 'gradle');
    if ($found) {
        $data = $line;
        break;
    }
}

$pid = null;
if ($data === null) {
    echo 'No Gradle process detected' . PHP_EOL;
} else {
    echo 'Gradle process detected' . PHP_EOL;
    echo PHP_EOL;
    
    $data = trim($data);
    $command_line = substr($data, 0, $p);
    $pid = substr($data, $p);
    
    $command_line = trim($command_line);
    $pid = trim($pid);
    
    echo 'data: ' . $data . PHP_EOL;
    echo PHP_EOL;
    echo 'command_line: ' . $command_line . PHP_EOL;
    echo PHP_EOL;
    echo 'pid: ' . $pid . PHP_EOL;
    echo PHP_EOL;
}

if ($pid !== null) {
    $cmd = 'TASKKILL /F /PID ' . $pid . ' /T';
    $result = shell_exec($cmd);
    echo $result;
}

exit();
