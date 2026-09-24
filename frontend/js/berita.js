let currentCategory = "all";

function filterCategory(category, btnElement) {
  currentCategory = category;

  const buttons = document.querySelectorAll(".filter-btn");
  buttons.forEach((btn) => {
    btn.className =
      "filter-btn px-6 py-2.5 rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-mho-blue-light hover:text-mho-blue text-xs font-bold uppercase tracking-wider transition-all flex-shrink-0 cursor-pointer";
  });
  btnElement.className =
    "filter-btn px-6 py-2.5 rounded-full bg-mho-blue text-white text-xs font-bold uppercase tracking-wider transition-all shadow-soft flex-shrink-0 cursor-pointer";

  filterAndSearchNews();
}

function filterAndSearchNews() {
  const searchQuery = document
    .getElementById("searchInput")
    .value.toLowerCase();
  const cards = document.querySelectorAll(".news-card");
  const emptyState = document.getElementById("emptySearchState");
  let visibleCount = 0;

  cards.forEach((card) => {
    const cardCategory = card.getAttribute("data-category");
    const cardTitle = card.getAttribute("data-title").toLowerCase();

    const matchCategory =
      currentCategory === "all" || cardCategory === currentCategory;
    const matchSearch = cardTitle.includes(searchQuery);

    if (matchCategory && matchSearch) {
      card.style.display = "flex";
      visibleCount++;
    } else {
      card.style.display = "none";
    }
  });

  if (visibleCount === 0) {
    emptyState.classList.remove("hidden");
  } else {
    emptyState.classList.add("hidden");
  }
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
