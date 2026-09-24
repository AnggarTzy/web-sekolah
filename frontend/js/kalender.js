function switchView(view) {
  const btnList = document.getElementById("btnViewList");
  const btnGrid = document.getElementById("btnViewGrid");
  const viewList = document.getElementById("viewList");
  const viewGrid = document.getElementById("viewGrid");

  if (view === "list") {
    btnList.className =
      "btn-view-active flex items-center justify-center w-10 h-10 rounded-lg transition-all";
    btnGrid.className =
      "btn-view-inactive flex items-center justify-center w-10 h-10 rounded-lg transition-all text-slate-500 hover:text-mho-blue";
    viewList.classList.remove("hidden");
    viewGrid.classList.add("hidden");
  } else {
    btnGrid.className =
      "btn-view-active flex items-center justify-center w-10 h-10 rounded-lg transition-all";
    btnList.className =
      "btn-view-inactive flex items-center justify-center w-10 h-10 rounded-lg transition-all text-slate-500 hover:text-mho-blue";
    viewGrid.classList.remove("hidden");
    viewList.classList.add("hidden");
  }
}

function filterEvents() {
  const categoryFilter = document.getElementById("filterCategory").value;
  const semesterFilter = document.getElementById("filterSemester").value;

  const eventBlocks = document.querySelectorAll(".event-block");
  let hasVisibleEvent = false;

  eventBlocks.forEach((block) => {
    const blockSemester = block.getAttribute("data-semester");
    let blockHasVisibleItem = false;
    const items = block.querySelectorAll(".event-item");

    items.forEach((item) => {
      const itemCategory = item.getAttribute("data-category");

      const matchCategory =
        categoryFilter === "all" || itemCategory === categoryFilter;
      const matchSemester =
        semesterFilter === "all" || blockSemester === semesterFilter;

      if (matchCategory && matchSemester) {
        item.style.display = "block";
        blockHasVisibleItem = true;
        hasVisibleEvent = true;
      } else {
        item.style.display = "none";
      }
    });

    block.style.display = blockHasVisibleItem ? "block" : "none";
  });

  const emptyState = document.getElementById("emptyState");
  if (hasVisibleEvent) {
    emptyState.classList.add("hidden");
  } else {
    emptyState.classList.remove("hidden");
  }
}
