<?php
require_once './header.php';

$maxOfQuestions = 20;
$result = 0;
$numOfquestions = 0;

for ($i = 0; $i <= $maxOfQuestions; $i++) {

    if (isset($_POST['correctAnswer-' . $i]) && isset($_POST['takingRadio' . $i])) {
        $numOfquestions++;

        if ($_POST['correctAnswer-' . $i] == $_POST['takingRadio' . $i]) {
            $result++;

        }
    }
}

?>

<div class="uk-container">
  <h1 class="uk-heading-large uk-text-center">Your result is: </h1>

  <h2 class="uk-heading-xlarge uk-text-center"><?=$result?></h2>
  <p class="uk-heading-large uk-text-center">Out of <?=$numOfquestions?></p>

</div>
