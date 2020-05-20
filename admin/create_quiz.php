<?php
require_once "../db.php";
require_once './header.php';
?>
<div class="uk-container">
  <h1 class="uk-heading-large">Create Quiz</h1>
  <form action="#" method="POST" id="form">
    <input name="QuizName" type="text" placeholder="Quiz name" class="uk-heading-small">
    <section id="QandAsection-1" class="QandAsection uk-card uk-card-default uk-margin-bottom">
      <div class="uk-card-header uk-flex uk-flex-between">
        <input name="Question-1" type="text" class="uk-card-title" placeholder="Question">
      </div>
      <div id="AnswerSection" class="uk-card-body">
        <label for="">Answers:</label>
        <input id="corrAnswer-1-Que-1" class="corrAnswer uk-radio" type="radio" name="makingRadio1"><input name="Answer-1-Que-1" type="text"></input>
      </div>
      <button id="AddAnswerBtn-1" type="button">Add answer</button>
    </section>
    <button id="AddQuestionBtn" type="button">Add question</button>
    <button type="submit">Save Quiz</button>
  </form>
</div>

</body>
</html>

<?php

$maxOfQuestions = 20;
$maxOfAnswers = 10;

if (isset($_POST['QuizName'])) {
    $quizName = $_POST['QuizName'];

    // echo $quizName;

    $quizId = insertQuizSQL($db, $quizName);

    for ($y = 1; $y <= $maxOfQuestions; $y++) {
        if (isset($_POST['Question-' . $y])) {
            $question = $_POST['Question-' . $y];

            // echo $question;

            $questionId = insertQuestionSQL($db, $question, $quizId);

            if (isset($_POST['makingRadio' . $y])) {
                $selectedAnswer = $_POST['makingRadio' . $y];

            }

            for ($i = 1; $i <= $maxOfAnswers; $i++) {
                if (isset($_POST['Answer-' . $i . '-Que-' . $y])) {
                    $answer = $_POST['Answer-' . $i . '-Que-' . $y];

                    // echo $answer;

                    $answerPk = insertAnswerSQL($db, $answer, $questionId);

                    if ($selectedAnswer == $i) {
                        $corrAnswer = $answerPk;
                        updateCorrAnswerSQL($db, $corrAnswer, $quizId, $questionId);

                    }

                } else {
                    break;
                }

            }

        }
    }
    // header('Location: ./detailed_quiz.php?quizId=' . $quizId);
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
  const newAnswer = document.querySelector('.uk-card-body');
  addAnswerBtnFunction(qAndAsection, addAnswerBtn, newAnswer);


});

addQuestionBtn.addEventListener('click', function() {
  addQuestionBtnFunction();

});

function addAnswerBtnFunction(QandAsection, addAnswerBtn, answerDiv) {
  answerCounter++;

  const form = document.querySelector('#form');
  const radioAnswer = document.createElement('input');
  const inputAnswer = document.createElement('input');

  radioAnswer.setAttribute('type', 'radio');
  radioAnswer.setAttribute('class', 'corrAnswer uk-radio');
  radioAnswer.setAttribute('name', 'makingRadio' + questionCounter);
  radioAnswer.setAttribute('value', answerCounter);

  inputAnswer.setAttribute('type', 'text');
  inputAnswer.setAttribute('name', 'Answer-' + answerCounter + '-Que-' + questionCounter);

  QandAsection.insertBefore(answerDiv, addAnswerBtn);
  answerDiv.appendChild(radioAnswer);
  answerDiv.appendChild(inputAnswer);

}

function addQuestionBtnFunction() {
  questionCounter++;
  answerCounter = 1;

  const form = document.querySelector('#form');
  const newQuestionSection = document.createElement('div');
  let newQuestionNameDiv = document.createElement('div');
  const inputQuestion = document.createElement('input');
  const newAddAnswerBtn = document.createElement('button');

  newQuestionSection.setAttribute('id', 'QandAsection-' + questionCounter);
  newQuestionSection.setAttribute('class', 'QandAsection uk-card uk-card-default uk-margin-bottom');

  newQuestionNameDiv.setAttribute('class', 'uk-card-header uk-flex uk-flex-between');

  inputQuestion.setAttribute('type', 'text');
  inputQuestion.setAttribute('name', 'Question-' + questionCounter);
  inputQuestion.setAttribute('class', 'uk-card-title');
  inputQuestion.setAttribute('placeholder', 'Question');

  newAddAnswerBtn.setAttribute('id', 'AddAnswerBtn-' + questionCounter);
  newAddAnswerBtn.setAttribute('type', 'button');
  newAddAnswerBtn.innerHTML = 'Add answer';


  form.insertBefore(newQuestionSection, addQuestionBtn);
  newQuestionNameDiv.appendChild(inputQuestion);
  newQuestionSection.appendChild(newQuestionNameDiv);
  newQuestionSection.appendChild(newAddAnswerBtn);

  const newAnswerInQue = document.createElement('div');
  const answerLable = document.createElement('lable');
  const radioAnswerInQue = document.createElement('input');
  const inputAnswerInQue = document.createElement('input');

  answerLable.innerHTML = 'Answers:';

  newAnswerInQue.setAttribute('class', 'uk-card-body');

  radioAnswerInQue.setAttribute('type', 'radio');
  radioAnswerInQue.setAttribute('value', answerCounter);
  radioAnswerInQue.setAttribute('class', 'corrAnswer uk-radio');
  radioAnswerInQue.setAttribute('name', 'makingRadio' + questionCounter);

  inputAnswerInQue.setAttribute('type', 'text');
  inputAnswerInQue.setAttribute('name', 'Answer-' + answerCounter + '-Que-' + questionCounter);

  newQuestionSection.insertBefore(newAnswerInQue, newAddAnswerBtn);
  newAnswerInQue.appendChild(answerLable);
  newAnswerInQue.appendChild(radioAnswerInQue);
  newAnswerInQue.appendChild(inputAnswerInQue);

  newAddAnswerBtn.addEventListener('click', function() {
    addAnswerBtnFunction(newQuestionSection, newAddAnswerBtn, newAnswerInQue);

  });
}

</script>
