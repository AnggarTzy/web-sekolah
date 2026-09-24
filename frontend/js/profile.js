function switchTab(tabId) {
  const panes = document.querySelectorAll(".tab-pane");
  panes.forEach((pane) => pane.classList.add("hidden"));

  const btnVisi = document.getElementById("btnVisi");
  const btnMisi = document.getElementById("btnMisi");
  const btnTujuan = document.getElementById("btnTujuan");

  [btnVisi, btnMisi, btnTujuan].forEach((btn) => {
    btn.className =
      "tab-btn-inactive px-4 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-headline font-bold text-xs uppercase tracking-wider transition-all duration-300 border-2 flex items-center gap-1.5 sm:gap-2";
  });

  if (tabId === "visi") {
    document.getElementById("contentVisi").classList.remove("hidden");
    btnVisi.className =
      "tab-btn-active px-4 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-headline font-bold text-xs uppercase tracking-wider transition-all duration-300 border-2 shadow-lg flex items-center gap-1.5 sm:gap-2";
  } else if (tabId === "misi") {
    document.getElementById("contentMisi").classList.remove("hidden");
    btnMisi.className =
      "tab-btn-active px-4 sm:px-8 py-4 sm:py-3.5 rounded-xl sm:rounded-2xl font-headline font-bold text-xs uppercase tracking-wider transition-all duration-300 border-2 shadow-lg flex items-center gap-1.5 sm:gap-2";
  } else if (tabId === "tujuan") {
    document.getElementById("contentTujuan").classList.remove("hidden");
    btnTujuan.className =
      "tab-btn-active px-4 sm:px-8 py-2.5 sm:py-3.5 rounded-xl sm:rounded-2xl font-headline font-bold text-xs uppercase tracking-wider transition-all duration-300 border-2 shadow-lg flex items-center gap-1.5 sm:gap-2";
  }
}
