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
  <i class="far fa-trash uk-align-right" id="deleteQuiz" style="font-size: 60px; margin: 0px; color: crimson;"></i>
    <h1 class="uk-heading-large uk-space-between" id="quizName"></h1>
    <form action="./edit_quiz.php" method="POST" id="editQuizNameForm">
      <div class="uk-hidden" id="quizNameInputDiv">
        <input class="uk-heading-large uk-form-width-large" id="quizNameInput" name="quizName">
        <button type="submit" class="uk-button uk-button-text">
          <i class="fas fa-save" style="font-size: 20px;"></i>
        </button>
        <i class="fas fa-times" id="closeEditQuizName" style="font-size: 20px;"></i>
      </div>
    </form>



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

quizName = document.querySelector('#quizName');
quizName.innerHTML = quiz.name + '  <i class="fas fa-edit" id="editQuizNameBtn" style="font-size: 20px;"></i>';
quizNameInput = document.querySelector('#quizNameInput');
quizNameInput.setAttribute('value', quiz.name);

hiddenQuizName = document.createElement('input');
hiddenQuizName.setAttribute('type', 'hidden');
hiddenQuizName.setAttribute('name', 'quizId');
hiddenQuizName.setAttribute('value', quiz.pk);

const editQuizNameForm = document.querySelector('#editQuizNameForm');
editQuizNameForm.appendChild(hiddenQuizName);

// const questionSection = document.querySelector('.questionSection');
// const answerSection = document.querySelector('.answerSection');
  const quizSection = document.querySelector('.quizSection');

  // let questionCounter = 0;

for(let i = 0; i < quiz.questions.length; i++) {

  const answerNumList = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];

  const question = quiz.questions[i];

  //detailed view
  let questionSection = document.createElement('section');
  questionSection.setAttribute('class', 'uk-card uk-card-default uk-margin-bottom');

  let questionDiv = document.createElement('div');
  questionDiv.setAttribute('class', 'uk-card-header uk-flex uk-flex-between');

  let questionHeader = document.createElement('h3');

  let editBtn = document.createElement('button');
  editBtn.setAttribute('class', 'uk-button uk-button-small uk-button-primary');
  editBtn.setAttribute('id', 'editBtn-' + i);
  editBtn.setAttribute('style', 'margin: auto 0;');
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

    if (question.correctAnswer === answer.pk) {
      answerP.setAttribute('class', 'uk-text-success uk-text-bold');
    }
    answerSection.appendChild(answerP);
    questionSection.appendChild(answerSection);
  }

  //edit wiew
  const editForm = document.createElement('form');
  editForm.setAttribute('id', 'editForm');
  editForm.setAttribute('action', 'edit_quiz.php');
  editForm.setAttribute('method', 'POST');

  let hiddenQueId = document.createElement('input');
  hiddenQueId.setAttribute('type', 'hidden');
  hiddenQueId.setAttribute('value', question.pk);
  hiddenQueId.setAttribute('name', 'questionId');

  let hiddenQuizId = document.createElement('input');
  hiddenQuizId.setAttribute('type', 'hidden');
  hiddenQuizId.setAttribute('value', quiz.pk);
  hiddenQuizId.setAttribute('name', 'quizId');

  let editQuestionSection = document.createElement('section');
  editQuestionSection.setAttribute('class', 'uk-card uk-card-default uk-margin-bottom uk-hidden');

  let editQuestionDiv = document.createElement('div');
  editQuestionDiv.setAttribute('class', 'uk-card-header uk-flex uk-flex-between');

  let editQuestionHeader = document.createElement('input');
  editQuestionHeader.setAttribute('type', 'text');
  editQuestionHeader.setAttribute('class', 'uk-card-title');
  editQuestionHeader.setAttribute('name', 'question');
  editQuestionHeader.setAttribute('value', question.question);

  let saveEditBtn = document.createElement('button');
  saveEditBtn.setAttribute('type', 'submit');
  saveEditBtn.setAttribute('class', 'uk-button uk-button-small uk-button-primary');
  saveEditBtn.setAttribute('id', 'saveEditBtn-' + i);
  saveEditBtn.innerHTML = 'Save';

  let exitEditBtn = document.createElement('button');
  exitEditBtn.setAttribute('type', 'submit');
  exitEditBtn.setAttribute('class', 'uk-button uk-button-small uk-button-primary uk-margin-left');
  exitEditBtn.setAttribute('id', 'exitEditBtn-' + i);
  exitEditBtn.innerHTML = 'Cancel';


  let editAddAnswerBtn = document.createElement('button');
  editAddAnswerBtn.setAttribute('type', 'button');
  editAddAnswerBtn.setAttribute('class', 'uk-button uk-button-default uk-button-small uk-margin-small-left uk-margin-small-bottom');
  editAddAnswerBtn.setAttribute('id', 'editAddAnswerBtn-' + i);
  editAddAnswerBtn.innerHTML = 'Add Answer';

  let btnDiv = document.createElement('div');


  editQuestionDiv.appendChild(editQuestionHeader);
  btnDiv.appendChild(saveEditBtn);
  btnDiv.appendChild(exitEditBtn);
  editQuestionDiv.appendChild(btnDiv);
  editQuestionSection.appendChild(editQuestionDiv);
  editForm.appendChild(editQuestionSection);
  editForm.appendChild(hiddenQueId);
  editForm.appendChild(hiddenQuizId);
  quizSection.appendChild(editForm);

  let editAnswerSection = document.createElement('section');
  editAnswerSection.setAttribute('class', 'uk-card-body');

  let editAnswerInputDiv = document.createElement('div');
  editAnswerInputDiv.setAttribute('class', 'editAnswerInputDiv');

  // let questionCounter= 0;

  for(let j = 0; j < question.answers.length; j++) {
    // let answerCounter = j;

    const answer = question.answers[j];

    let editAnswerInputDiv = document.createElement('div');
    editAnswerInputDiv.setAttribute('class', 'editAnswerInputDiv');
    editAnswerInputDiv.setAttribute('id', 'answer-' + j);

    let editAnswerP = document.createElement('input');
    editAnswerP.setAttribute('type', 'text');
    editAnswerP.setAttribute('name', 'pk-' + answer.pk);
    editAnswerP.setAttribute('value', answer.answer);

    const editRadioAnswer = document.createElement('input');
    editRadioAnswer.setAttribute('type', 'radio');
    editRadioAnswer.setAttribute('class', 'corrAnswer uk-radio uk-margin-small-left uk-margin-small-right');
    editRadioAnswer.setAttribute('name', 'editRadio-' + question.pk);
    editRadioAnswer.setAttribute('value', answer.pk);

    const deleteI = document.createElement('i');
    deleteI.setAttribute('class', 'far fa-trash');

    if (question.correctAnswer === answer.pk) {
      editRadioAnswer.checked = true;
    }

    editAnswerInputDiv.appendChild(editRadioAnswer);
    editAnswerInputDiv.appendChild(editAnswerP);
    editAnswerInputDiv.appendChild(deleteI);
    editAnswerSection.appendChild(editAnswerInputDiv);
    editQuestionSection.appendChild(editAnswerSection);
    editQuestionSection.appendChild(editAddAnswerBtn);

    deleteI.addEventListener('click', function() {

      removeElement('answer-' + j);



  })
  }



  editBtn.addEventListener('click', function () {

    questionSection.classList.add('uk-hidden');
    editQuestionSection.classList.replace('uk-hidden', 'uk-visible');
  })

  exitEditBtn.addEventListener('click', function() {
    questionSection.classList.replace('uk-hidden');
    editQuestionSection.classList.add('uk-hidden', 'uk-visible');
  })

  let questionId = question.pk;
  editAddAnswerBtn.addEventListener('click', function() {
    let answerCounter = answerNumList.shift();
    addAnswerBtnFunction(editAnswerSection, editAddAnswerBtn, editAnswerInputDiv, answerCounter, questionId, answerNumList)
  })





}


editQuizNameBtn = document.querySelector('#editQuizNameBtn');
quizNameInputDiv = document.querySelector('#quizNameInputDiv');


editQuizNameBtn.addEventListener('click', function() {
  quizName.classList.add('uk-hidden');
  quizNameInputDiv.classList.remove('uk-hidden');


});

closeEditQuizName = document.querySelector('#closeEditQuizName');
closeEditQuizName.addEventListener('click', function() {
  quizName.classList.remove('uk-hidden');
  quizNameInputDiv.classList.add('uk-hidden');
})

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

function addAnswerBtnFunction(QandAsection, addAnswerBtn, answerDiv, counter, questionId, answerNumList) {

  const form = document.querySelector('#editForm');
  const answerInputDiv = document.createElement('div');
  const radioAnswer = document.createElement('input');
  const inputAnswer = document.createElement('input');
  const deleteI = document.createElement('i');

  deleteI.setAttribute('class', 'far fa-trash');

  answerInputDiv.setAttribute('class', 'answer_input');
  answerInputDiv.setAttribute('id', 'newAnswer-' + counter);

  radioAnswer.setAttribute('type', 'radio');
  radioAnswer.setAttribute('class', 'corrAnswer uk-radio uk-margin-small-left uk-margin-small-right');
  radioAnswer.setAttribute('name', 'editRadio-' + questionId);
  radioAnswer.setAttribute('value', counter);

  inputAnswer.setAttribute('type', 'text');
  inputAnswer.setAttribute('name', 'counter-' + counter);

  answerInputDiv.appendChild(radioAnswer);
  answerInputDiv.appendChild(inputAnswer);
  answerInputDiv.appendChild(deleteI);
  QandAsection.appendChild(answerInputDiv);

  deleteI.addEventListener('click', function() {
      removeElement('newAnswer-' + counter);

      answerNumList.unshift(counter);
      console.log(answerNumList);

  })

}

function removeElement(elementId) {
let element = document.getElementById(elementId);
element.parentNode.removeChild(element);
}

</script>