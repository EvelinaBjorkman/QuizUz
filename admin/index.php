<?php
require_once '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/styles.css" />
    <!-- UIkit CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/css/uikit.min.css"
    />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/js/uikit-icons.min.js"></script>
    <title>QuizUz Admin</title>
  </head>
  <body class="admin_index_body">
  <div uk-sticky="top: 0">
    <nav class="uk-navbar-container uk-margin " uk-navbar>
      <div class="uk-navbar-center">
        <a class="uk-navbar-item uk-logo" href="./index.php">QuizUz</a>
      </div>
    </nav>
  </div>
    <div class="uk-container">
      <div class="transperant_background">
        <section class="text-container">
          <h1 class="uk-heading-xlarge">Admin</h1>
          <a href="./create_quiz.php"
            ><button id="createQuizBtn" class="uk-button uk-button-secondary">
              CREATE NEW QUIZ
            </button></a
          >
          <p class="uk-text-large">
            <b class="uk-text-bolder">Here´s a list of created quizes</b>, click
            on one to se the questions & answers and to be able to edit the
            quiz.
          </p>
        </section>
      </div>
    </div>
    </div>
  </body>
</html>

<?php

$sql = "SELECT * FROM quiz";
$stmt = $db->prepare($sql);
$stmt->execute();
echo "<div class='uk-container list-container'>";
echo "<ul class='uk-list'>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $quizName = $row['name'];
    $quizPk = $row['pk'];

    // print_r($row);
    // echo "<ul>";
    echo "<li><a href='./detailed_quiz.php?quizId=$quizPk' class='uk-link-text uk-text-large'>" . $quizName . "</a></li>";
// echo "<li>" . $row['LastName'] . "</li>";
    // echo "</tr>";
}
echo "</ul>";
echo "</div>";

// $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>