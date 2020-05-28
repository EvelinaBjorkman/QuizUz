<?php
require_once '../db.php';

if (isset($_POST['quizId'])) {
    $quizId = $_POST['quizId'];

    resetCorrAnswersSQL($db, $quizId);
    deleteAnswerSQL($db, $quizId);
    deleteQuestionSQL($db, $quizId);
    deleteQuizSQL($db, $quizId);

    header('Location: ./index.php');

}

function deleteQuestionSQL($db, $quizId)
{
    $sql = "DELETE FROM question
            WHERE quizId = :quizId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->execute();
}

function deleteAnswerSQL($db, $quizId)
{
    $sql = "DELETE FROM answer
            WHERE pk IN (SELECT a.pk FROM answer a INNER JOIN question q ON q.pk = a.questionId WHERE q.quizId = :quizId)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->execute();
}

function resetCorrAnswersSQL($db, $quizId)
{
    $sql = "UPDATE question
            SET correctAnswer = NULL
            WHERE pk IN (SELECT pk FROM question WHERE quizId = :quizId)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->execute();
}

function deleteQuizSQL($db, $quizId)
{
    $sql = "DELETE FROM quiz
            WHERE pk = :quizId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->execute();

}
