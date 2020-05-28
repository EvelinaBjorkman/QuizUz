<?php
require_once '../db.php';

if (isset($_POST['questionId'])) {
    $questionId = $_POST['questionId'];
    $quizId = $_POST['quizId'];

    resetCorrAnswerSQL($db, $questionId);
    deleteAnswerSQL($db, $questionId);
    deleteQuestionSQL($db, $questionId);

    header('Location: ./detailed_quiz.php?quizId=' . $quizId);
}

function deleteQuestionSQL($db, $questionId)
{
    $sql = "DELETE FROM question
            WHERE pk = :questionId";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':questionId', $questionId);
    $stmt->execute();

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
