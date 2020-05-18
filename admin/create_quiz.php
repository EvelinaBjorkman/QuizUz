<?php
require_once "../db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/styles.css" />
  <title>Document</title>
</head>
<body>
  <h1>Create Quiz</h1>
  <form action="#" method="POST" id="form">
    <label for="QuizName">Quiz name</label>
    <input name="QuizName" type="text">
    <section id="QandAsection-1" class="QandAsection">
      <label for="">Questions:</label>
      <input name="Question-1" type="text">
      <div id="AnswerSection">
        <label for="">Answers</label>
        <input name="corrAnswer-1-Que-1" class="corrAnswer" type="radio"><input name="Answer-1-Que-1" type="text"></input>
      </div>
      <button id="AddAnswerBtn-1" type="button">Add answer</button>
    </section>
    <button id="AddQuestionBtn" type="button">Add question</button>
    <button type="submit">Save Quiz</button>
  </form>
</body>
</html>

<?php

$maxOfQuestions = 20;
$maxOfAnswers = 10;

if (isset($_POST['QuizName'])) {
    $quizName = $_POST['QuizName'];

    $quizId = insertQuizSQL($db, $quizName);

    for ($y = 1; $y <= $maxOfQuestions; $y++) {
        if (isset($_POST['Question-' . $y])) {
            $question = $_POST['Question-' . $y];

            $questionId = insertQuestionSQL($db, $question, $quizId);

            for ($i = 1; $i <= $maxOfAnswers; $i++) {
                echo 'Answer loop: ' . $i . ' / ';
                echo 'Answer loop question id: ' . $y . ' / ';
                if (isset($_POST['Answer-' . $i . '-Que-' . $y])) {
                    $answer = $_POST['Answer-' . $i . '-Que-' . $y];

                    echo 'Answer: ' . $answer . ' / ';

                    $answerPk = insertAnswerSQL($db, $answer, $questionId);

                    if (isset($_POST['corrAnswer-' . $i . '-Que-' . $y])) {
                        $corrAnswer = $answerPk;

                        echo 'CorrAnswer: ' . $corrAnswer . ' / ';

                        updateCorrAnswerSQL($db, $corrAnswer, $quizId, $questionId);

                    }

                } else {
                    break;
                }

            }
        }
    }
}

function insertQuizSQL($db, $quizName)
{
    $sql = "INSERT INTO quiz (name)
  VALUES (:name)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':name', $quizName);
    $stmt->execute();

    return $db->lastInsertId();
}

function insertQuestionSQL($db, $question, $quizId)
{
    $sql = "INSERT INTO question (question, quizId)
  VALUES (:question, :quizId)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':question', $question);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->execute();

    return $db->lastInsertId();
}

function insertAnswerSQL($db, $answer, $questionId)
{
    $sql = "INSERT INTO answer (answer, questionId)
        VALUES (:answer, :questionId)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':answer', $answer);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();

    return $db->lastInsertId();
}

function updateCorrAnswerSQL($db, $corrAnswer, $quizId, $questionId)
{
    $sql = "UPDATE question
  SET correctAnswer = :correctAnswer
  WHERE quizId = :quizId
  AND pk = :questionId";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':correctAnswer', $corrAnswer);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();
}
?>

<script>

let answerCounter = 1;
let questionCounter = 1;

let addAnswerBtn = document.querySelector('#AddAnswerBtn-' + questionCounter);
const answerSection = document.querySelector('#AnswerSection');
const addQuestionBtn = document.querySelector('#AddQuestionBtn');
const qAndAsection = document.querySelector("#QandAsection-1");


addAnswerBtn.addEventListener('click', function() {
  addAnswerBtnFunction(qAndAsection, addAnswerBtn);
});

addQuestionBtn.addEventListener('click', function() {
  addQuestionBtnFunction();
});

function addAnswerBtnFunction(QandAsection, addAnswerBtn) {
  answerCounter++;

  console.log(QandAsection);

  const form = document.querySelector('#form');
  const newAnswer = document.createElement('div');
  const radioAnswer = document.createElement('input');
  const inputAnswer = document.createElement('input');

  radioAnswer.setAttribute('type', 'radio');
  radioAnswer.setAttribute('name', 'corrAnswer-' + answerCounter + '-Que-' + questionCounter);
  radioAnswer.setAttribute('class', 'corrAnswer');

  inputAnswer.setAttribute('type', 'text');
  inputAnswer.setAttribute('name', 'Answer-' + answerCounter + '-Que-' + questionCounter);

  QandAsection.insertBefore(newAnswer, addAnswerBtn);
  newAnswer.appendChild(radioAnswer);
  newAnswer.appendChild(inputAnswer);
}

function addQuestionBtnFunction() {

  questionCounter++;
  answerCounter = 1;

  const form = document.querySelector('#form');
  const newQuestionSection = document.createElement('div');
  const inputQuestion = document.createElement('input');
  const newAddAnswerBtn = document.createElement('button');

  newQuestionSection.setAttribute('id', 'QandAsection-' + questionCounter);
  newQuestionSection.setAttribute('class', 'QandAsection');

  inputQuestion.setAttribute('type', 'text');
  inputQuestion.setAttribute('name', 'Question-' + questionCounter);

  newAddAnswerBtn.setAttribute('id', 'AddAnswerBtn-' + questionCounter);
  newAddAnswerBtn.setAttribute('type', 'button');
  newAddAnswerBtn.innerHTML = 'Add answer';

  form.insertBefore(newQuestionSection, addQuestionBtn);
  newQuestionSection.appendChild(inputQuestion);
  newQuestionSection.appendChild(newAddAnswerBtn);

  const newAnswerInQue = document.createElement('div');
  const radioAnswerInQue = document.createElement('input');
  const inputAnswerInQue = document.createElement('input');

  radioAnswerInQue.setAttribute('type', 'radio');
  radioAnswerInQue.setAttribute('name', 'corrAnswer-' + answerCounter + '-Que-' + questionCounter);
  radioAnswerInQue.setAttribute('class', 'corrAnswer');

  inputAnswerInQue.setAttribute('type', 'text');
  inputAnswerInQue.setAttribute('name', 'Answer-' + answerCounter + '-Que-' + questionCounter);

  newQuestionSection.insertBefore(newAnswerInQue, newAddAnswerBtn);
  newAnswerInQue.appendChild(radioAnswerInQue);
  newAnswerInQue.appendChild(inputAnswerInQue);

  newAddAnswerBtn.addEventListener('click', function() {

    addAnswerBtnFunction(newQuestionSection, newAddAnswerBtn);
  });
}








</script>
