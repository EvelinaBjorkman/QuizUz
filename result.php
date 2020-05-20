<?php

$maxOfQuestions = 20;
$result = 0;

for ($i = 0; $i <= $maxOfQuestions; $i++) {

    if (isset($_POST['correctAnswer-' . $i]) && isset($_POST['takingRadio' . $i])) {

        if ($_POST['correctAnswer-' . $i] == $_POST['takingRadio' . $i]) {
            $result++;
        }

    }

}

echo $result;
