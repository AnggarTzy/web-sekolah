function filterCategory(category, btnElement) {
  const buttons = document.querySelectorAll(".filter-btn");
  buttons.forEach((btn) => {
    btn.className =
      "filter-btn filter-btn-inactive px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all border-2 flex-shrink-0 cursor-pointer";
  });
  btnElement.className =
    "filter-btn filter-btn-active px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all border-2 flex-shrink-0 cursor-pointer";

  const cards = document.querySelectorAll(".faculty-card");
  cards.forEach((card) => {
    const cardCategory = card.getAttribute("data-category");
    if (category === "all" || cardCategory === category) {
      card.style.display = "flex";
    } else {
      card.style.display = "none";
    }
  });
}

function scrollSection(sliderId, direction) {
  const container = document.getElementById(sliderId);
  const scrollAmount = 400;
  if (direction === "left") {
    container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
  } else {
    container.scrollBy({ left: scrollAmount, behavior: "smooth" });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const defaultBtn = document.querySelector('.filter-btn[onclick*="all"]');
  if (defaultBtn) {
    filterCategory("all", defaultBtn);
  }
});
