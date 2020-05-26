<?php

require_once '../db.php';
echo 'hej';

$maxOfAnswers = 20;

if (isset($_POST['questionId']) && isset($_POST['quizId'])) {

    $questionId = $_POST['questionId'];
    $quizId = $_POST['quizId'];

    $sql = "SELECT pk FROM answer
        WHERE questionId = :questionId";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    resetCorrAnswerSQL($db, $questionId);
    deleteAnswerSQL($db, $questionId);

    for ($i = 0; $i < count($results); $i++) {
        $answerPk = $results[$i]['pk'];

        if (isset($_POST['pk-' . $answerPk])) {
            $answer = $_POST['pk-' . $answerPk];
            $newAnswerPk = insertAnswerSQL($db, $answer, $questionId);
        }

        if (isset($_POST['editRadio-' . $questionId])) {
            $selectedAnswer = $_POST['editRadio-' . $questionId];

            if ($selectedAnswer == $answerPk) {
                updateCorrAnswerSQL($db, $newAnswerPk, $questionId);
            }
        }

    }

    for ($i = 1; $i <= $maxOfAnswers; $i++) {

        if (isset($_POST['counter-' . $i])) {
            $newAnswer = $_POST['counter-' . $i];

            $newAnswerPk = insertAnswerSQL($db, $newAnswer, $questionId);

            if (isset($_POST['editRadio-' . $i])) {
                $selectedAnswer = $_POST['editRadio-' . $i];

                if ($selectedAnswer == $i) {
                    $corrAnswer = $newAnswerPk;
                    updateCorrAnswerSQL($db, $corrAnswer, $questionId);
                }
            }

        }

    }

    if (isset($_POST['question'])) {
        $question = $_POST['question'];
        updateQuestionNameSQL($db, $questionId, $question);
    }

    header('Location: ./detailed_quiz.php?quizId=' . $quizId);

}

if (isset($_POST['quizName'])) {
    $quizName = $_POST['quizName'];
    $quizId = $_POST['quizId'];

    updateQuizNameSQL($db, $quizName, $quizId);

    header('Location: ./detailed_quiz.php?quizId=' . $quizId);
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

function deleteAnswerSQL($db, $questionId)
{
    $sql = "DELETE FROM answer
            WHERE questionId = :questionId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();

    return $db->lastInsertId();
}

function resetCorrAnswerSQL($db, $questionId)
{
    $sql = "UPDATE question
            SET correctAnswer = NULL
            WHERE pk = :questionId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();

    return $db->lastInsertId();
}

function updateCorrAnswerSQL($db, $corrAnswer, $questionId)
{
    $sql = "UPDATE question
    SET correctAnswer = :correctAnswer
    WHERE pk = :questionId";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':correctAnswer', $corrAnswer);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();
}

function updateQuestionNameSQL($db, $questionId, $question)
{
    $sql = "UPDATE question
  SET question = :question
  WHERE pk = :questionId";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':question', $question);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();
}

function updateQuizNameSQL($db, $quizName, $quizId)
{
    $sql = "UPDATE quiz
  SET name = :quizName
  WHERE pk = :quizId";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':quizName', $quizName);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->execute();
}
