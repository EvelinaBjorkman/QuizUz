<?php
require_once '../db.php';

$maxOfAnswers = 20;

if (isset($_POST['Question'])) {
    $questionName = $_POST['Question'];
    $quizId = $_POST['quizId'];

    $questionId = insertQuestionSQL($db, $questionName, $quizId);

    if (isset($_POST['editRadio'])) {
        $selectedAnswer = $_POST['editRadio'];

        echo 'editRadio isset';

        echo $selectedAnswer;

    }

    for ($i = 0; $i <= $maxOfAnswers; $i++) {
        if (isset($_POST['counter-' . $i])) {
            $answer = $_POST['counter-' . $i];

            $answerPk = insertAnswerSQL($db, $answer, $questionId);

            if ($selectedAnswer == $i) {
                $corrAnswer = $answerPk;
                updateCorrAnswerSQL($db, $corrAnswer, $quizId, $questionId);

            }

        } else {
            break;
        }

    }

    header('Location: ./detailed_quiz.php?quizId=' . $quizId);
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
