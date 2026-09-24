if ("scrollRestoration" in history) {
  history.scrollRestoration = "manual";
}
window.addEventListener("beforeunload", () => {
  window.scrollTo(0, 0);
});

// Init AOS
AOS.init({
  duration: 600,
  once: true,
  offset: 40,
  easing: "cubic-bezier(0.16, 1, 0.3, 1)",
});

// Back to top button
const backToTopBtn = document.getElementById("backToTopBtn");
if (backToTopBtn) {
  window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
      backToTopBtn.classList.remove("hidden");
    } else {
      backToTopBtn.classList.add("hidden");
    }
  });
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}

// Mobile navigation
function toggleMobileNav() {
  const menu = document.getElementById("mobileNav");
  menu.classList.toggle("hidden");
}

function toggleMobileSubmenu(btn) {
  const subMenu = btn.nextElementSibling;
  const icon = btn.querySelector(".material-symbols-outlined");
  subMenu.classList.toggle("hidden");
  icon.style.transform = subMenu.classList.contains("hidden")
    ? "rotate(0deg)"
    : "rotate(180deg)";
}
