function handleFormSubmit(event) {
  event.preventDefault();

  const form = document.getElementById("feedbackForm");
  const alertBox = document.getElementById("successAlert");

  form.reset();
  alertBox.classList.remove("hidden");

  alertBox.scrollIntoView({ behavior: "smooth", block: "nearest" });

  setTimeout(() => {
    alertBox.classList.add("hidden");
  }, 6000);
}
