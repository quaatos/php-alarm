<?php
$jsonfile = file_get_contents('alarms.json');
$decoded = json_decode($jsonfile);

if (isset($argv[1]) && isset($argv[2]) && isset($argv[3])) {
    $hour = $argv[1];
    $minutes = $argv[2];
    $seconds = $argv[3];
} else {
    if ($argv[1] == "-t") {
        echo("Timers: " . PHP_EOL);
        for($i = 0; $i < count($decoded); $i++) {
            echo("\e[32m" . $i . " ==> " . $decoded[$i]->Label . " | " . $decoded[$i]->time . PHP_EOL . "\e[39m");
        }
    }
    $option = readline("Set timer: ");
    if ($option <= count($decoded) && $option >= 0) {
        if (is_numeric($option)) {
            system('clear || cls');
            echo("Alarm set to >>> " . $decoded[$option]->time);

            $split = explode(':', $decoded[$option]->time);

            $hour = $split[0];
            $minutes = $split[1];
            $seconds = $split[2];
        }
    }
}

$alarm = "$hour:$minutes:$seconds";
$espeak = $decoded[$option]->Label;

while(true) {
    $datehour = date('H');
    $dateminute = date('i');
    $datesecond = date('s');

    $date = "$datehour:$dateminute:$datesecond";

    if ($alarm == $date) {
        system('espeak "' . $espeak . '" && clear || cls');
        system("mpg123 sounds/" . arrayEntry());
        exit(0);
        
    }
    sleep(1);
}

function arrayEntry() {
    $files = scandir('sounds/');
    array_shift($files);
    array_shift($files);
    
    return $files[array_rand($files, 1)];
}
?>