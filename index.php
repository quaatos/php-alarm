<?php
system("clear || cls");
$jsonfile = file_get_contents('alarms.json');
$decoded = json_decode($jsonfile);

if (isset($argv[1]) && isset($argv[2]) && isset($argv[3])) {
    $hour = $argv[1];
    $minutes = $argv[2];
    $seconds = $argv[3];
} else {
    if (isset($argv)) {
        $arguments = ['-t', '-s'];
        for($j = 0; $j < count($argv); $j++) {
            if (in_array($argv[$j], $arguments)) {
                if ($argv[$j] == "-t") {
                    echo("Timers: " . PHP_EOL);
                    for($i = 0; $i < count($decoded); $i++) {
                        echo("\e[32m" . $i . " ==> " . $decoded[$i]->Label . " | " . $decoded[$i]->time . PHP_EOL . "\e[39m");
                    }
                }

                if ($argv[$j] == '-s') {
                    $again = false;
                    while($again = true) {
                        system('clear || cls');
                        echo "Setting new timer e.g: 08:30:12" . PHP_EOL;
                        $settime = readline("New time: " . PHP_EOL);
                        $setlabel = readline("New label: " . PHP_EOL);
                        $sure = readline("Are you sure [Y/n/]: " . PHP_EOL);

                        if (strtoupper($sure) == "Y" || $sure == "") {
                            addTimer($settime, $setlabel);

                        } elseif(strtoupper($sure) == "N") { //TODO: Fix json file writing.
                            echo "resetting...";
                            sleep(2);
                            $again = true;
                        }
                    }
                }
            }
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

function addTimer($time, $label) {
    $open = fopen("alarms.json", "r+");
    if (fwrite($open, "{\"Label\":\"$label\", \"time\":\"$time\"") . PHP_EOL) {
        echo "Timer added! Restarting...\n";
        fclose($open);
        sleep(1);
        system("php index.php -t");
    }
}
?>