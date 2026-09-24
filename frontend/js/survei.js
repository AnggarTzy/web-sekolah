document.querySelectorAll(".rating-radio").forEach((radio) => {
  radio.addEventListener("change", updateProgress);
});

function updateProgress() {
  const questions = document.querySelectorAll(".question-item");
  let answered = 0;
  questions.forEach((q) => {
    const checked = q.querySelector('input[type="radio"]:checked');
    if (checked) answered++;
  });
  const total = questions.length;
  const percent = Math.round((answered / total) * 100);
  document.getElementById("progressFill").style.width = percent + "%";
  document.getElementById("progressText").textContent = percent + "%";
}

function handleSurveySubmit(event) {
  event.preventDefault();
  const form = document.getElementById("surveyForm");
  const alertBox = document.getElementById("surveySuccess");
  form.reset();
  document.getElementById("progressFill").style.width = "0%";
  document.getElementById("progressText").textContent = "0%";
  alertBox.classList.remove("hidden");
  alertBox.scrollIntoView({ behavior: "smooth", block: "nearest" });
  setTimeout(() => {
    alertBox.classList.add("hidden");
  }, 6000);
}

function handleAlumniSubmit(event) {
  event.preventDefault();
  const form = document.getElementById("alumniForm");
  const alertBox = document.getElementById("alumniSuccess");
  form.reset();
  alertBox.classList.remove("hidden");
  alertBox.scrollIntoView({ behavior: "smooth", block: "nearest" });
  setTimeout(() => {
    alertBox.classList.add("hidden");
  }, 6000);
}
