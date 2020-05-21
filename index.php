<?php
require_once './db.php';
require_once './header.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/styles.css" />
    <!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/css/uikit.min.css" />

<!-- UIkit JS -->
<script src="https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/js/uikit-icons.min.js"></script>
    <title>QuizUz</title>
  </head>
  <body>
    <div class="uk-container">
      <h1 class="uk-heading-xlarge uk-text-center">QuizUz</h1>
      <h2 class="uk-heading-medium uk-text-center">Enter quiz id to take a quiz</h2>
      <form class="uk-flex uk-flex-center uk-flex-column" action="./take_quiz.php" method="GET">
        <div class="uk-flex uk-flex-center uk-margin">
          <input class="uk-input uk-form-large uk-width-medium uk-text-rigth takeQuizInput" name="quizId" type="text">
          <button class="uk-button uk-button-secondary uk-width-medium" type="submit">Take quiz</button>
        </div>
      </form>
      <p class="uk-text-center uk-text-large">Or select one from this list:</p>
    </div>


<?php
$sql = "SELECT * FROM quiz";
$stmt = $db->prepare($sql);
$stmt->execute();

echo "<div class='uk-container list-container'>";
echo "<ul class='uk-list'>";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $quizName = $row['name'];
    $quizPk = $row['pk'];

    echo "<li><a href='./take_quiz.php?quizId=$quizPk' class='uk-link-text uk-text-large'>" . $quizName . "</a></li>";
}

echo "</ul>";
echo "</div>";

?>
</body>
</html>