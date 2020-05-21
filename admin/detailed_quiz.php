<?php
require_once '../db.php';
require_once './header.php';

if (isset($_GET['quizId'])) {
    $quizId = $_GET['quizId'];

    $sql = "SELECT * FROM quiz
  LEFT JOIN question as q ON q.quizId = quiz.pk
  LEFT JOIN answer as a ON q.pk = a.questionId
  WHERE quiz.pk = :quizId";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':quizId', $quizId);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="uk-container">

  <section class="quizSection">
    <h1 class="uk-heading-large" id="quizName"></h1>


  </section>
</div>




</body>
</html>

<script>
let quizArray = <?php echo json_encode($results) ?>;

const quiz = {
  pk: quizArray[0].quizId,
  name: quizArray[0].name,
  created: quizArray[0].created,
  questions: []
};

for (let i = 0; i < quizArray.length; i++) {
  const row = quizArray[i];
  const question = parseQuestion(row);
  const answer = parseAnswer(row);

  const foundQuestion = findQuestion(quiz, question);
  if (foundQuestion) {
    foundQuestion.answers.push(answer);
  } else {
    question.answers.push(answer);
    quiz.questions.push(question);
  }
}

document.querySelector('#quizName').innerHTML = quiz.name;
// const questionSection = document.querySelector('.questionSection');
// const answerSection = document.querySelector('.answerSection');
  const quizSection = document.querySelector('.quizSection');

for(let i = 0; i < quiz.questions.length; i++) {

  const question = quiz.questions[i];

  let questionSection = document.createElement('section');

  questionSection.setAttribute('class', 'uk-card uk-card-default uk-margin-bottom');
  let questionDiv = document.createElement('div');
  questionDiv.setAttribute('class', 'uk-card-header uk-flex uk-flex-between');
  let questionHeader = document.createElement('h3');
  let editBtn = document.createElement('button');
  editBtn.setAttribute('class', 'uk-button uk-button-small uk-button-primary');
  editBtn.innerHTML = 'Edit';
  questionHeader.setAttribute('class', 'uk-card-title');
  questionHeader.innerHTML = question.question;

  questionDiv.appendChild(questionHeader);
  questionDiv.appendChild(editBtn);
  questionSection.appendChild(questionDiv);
  quizSection.appendChild(questionSection);

  let answerSection = document.createElement('section');
  answerSection.setAttribute('class', 'uk-card-body');

  for(let j = 0; j < question.answers.length; j++) {

    const answer = question.answers[j];

    let answerP = document.createElement('p');
    answerP.innerHTML = answer.answer;
    console.log(question.correctAnswer);
    console.log(answer.pk);


    if (question.correctAnswer === answer.pk) {
      answerP.setAttribute('class', 'uk-text-success uk-text-bold');
    }
    answerSection.appendChild(answerP);
    questionSection.appendChild(answerSection);
  }
}






function parseQuestion(row) {
  return {
    pk: row.questionId,
    question: row.question,
    correctAnswer: row.correctAnswer,
    answers: []
  };
}

function parseAnswer(row) {
  return {
    pk: row.pk,
    answer: row.answer
  };
}

function findQuestion(quiz, question) {
  return quiz.questions.find(q => q.pk === question.pk);
}

</script>