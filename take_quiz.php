<?php
require_once './db.php';
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

  <h1 class="uk-heading-large" id="quizName"></h1>
  <form action="./result.php" method="POST">
    <section class="quizSection">

    </section>
    <button class="uk-button uk-button-secondary" type="submit">Submit</button>
  </form>

</div>

</body>
</html>

<script>
let quizArray = <?php echo json_encode($results) ?>;
let result = 0;

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
  const quizSection = document.querySelector('.quizSection');

  for(let i = 0; i < quiz.questions.length; i++) {

  const question = quiz.questions[i];

  let questionSection = document.createElement('section');

  questionSection.setAttribute('class', 'uk-card uk-card-default uk-margin-bottom');
  let questionDiv = document.createElement('div');
  questionDiv.setAttribute('class', 'uk-card-header uk-flex uk-flex-between');
  let questionHeader = document.createElement('h3');
  questionHeader.setAttribute('class', 'uk-card-title');
  questionHeader.innerHTML = question.question;

  questionDiv.appendChild(questionHeader);
  questionSection.appendChild(questionDiv);
  quizSection.appendChild(questionSection);

  let answerSection = document.createElement('section');
  answerSection.setAttribute('class', 'uk-card-body');

  let hiddenField = document.createElement('input');
  hiddenField.setAttribute('type', 'hidden');
  hiddenField.setAttribute('name', 'correctAnswer-' + i);
  hiddenField.setAttribute('value', question.correctAnswer);


  questionSection.appendChild(hiddenField);

  for(let j = 0; j < question.answers.length; j++) {

    const answer = question.answers[j];

    let answerRadioDiv = document.createElement('div');
    answerRadioDiv.setAttribute('class', 'uk-margin uk-grid-small uk-child-width-auto uk-grid');

    let answerRadio = document.createElement('input');
    answerRadio.setAttribute('type', 'radio');
    answerRadio.setAttribute('class', 'uk-radio');
    answerRadio.setAttribute('value', answer.pk)
    answerRadio.setAttribute('name', 'takingRadio'+ i);

    let answerP = document.createElement('p');
    answerP.innerHTML = answer.answer;

    answerRadioDiv.appendChild(answerRadio);
    answerRadioDiv.appendChild(answerP);
    answerSection.appendChild(answerRadioDiv);
    questionSection.appendChild(answerSection);

  }
  // const submitBtn = document.querySelector('#submitBtn');
  // submitBtn.addEventListener('click', function() {
  //   displayResults(question);

  // });

}

// function displayResults(question) {
//   quizSection.style.display = "none";
//     submitBtn.style.display = "none";

//     let radios = document.querySelectorAll('.uk-radio');

//     for(const radio of radios) {

//       if(radio.checked && question.correctAnswer === radio.value) {
//         result ++;

//       }
//     }

//     container = document.querySelector('.uk-container');
//     resultShow = document.createElement('h2');
//     resultShow.innerHTML = result;
//     container.appendChild(resultShow);
// }

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
