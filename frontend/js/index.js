function toggleFaq(btn) {
  const content = btn.nextElementSibling;
  const icon = btn.querySelector(".material-symbols-outlined");
  content.classList.toggle("hidden");
  if (content.classList.contains("hidden")) {
    icon.textContent = "add";
    icon.style.transform = "rotate(0deg)";
  } else {
    icon.textContent = "remove";
    icon.style.transform = "rotate(180deg)";
  }
}

// Video Modal
function toggleVideoModal() {
  const modal = document.getElementById("videoModal");
  const iframe = document.getElementById("youtubeIframe");
  modal.classList.toggle("hidden");

  if (!modal.classList.contains("hidden")) {
    iframe.src = "https://www.youtube.com/embed/iYj5YKzO6hU?autoplay=1&rel=0";
  } else {
    iframe.src = "";
  }
}

// Alumni Slider
function scrollAlumni(direction) {
  const container = document.getElementById("alumniSlider");
  const scrollAmount = container.clientWidth > 768 ? 380 : 300;
  if (direction === "left") {
    container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
  } else {
    container.scrollBy({ left: scrollAmount, behavior: "smooth" });
  }
}

// Counter Statistik
function startCounters() {
  const counters = document.querySelectorAll("[data-target]");
  const speed = 200;

  counters.forEach((counter) => {
    const target = +counter.getAttribute("data-target");
    const suffix = counter.getAttribute("data-suffix") || "";
    let count = 0;

    const updateCount = () => {
      const inc = target / speed;
      if (count < target) {
        count += inc;
        counter.innerText = Math.ceil(count) + suffix;
        setTimeout(updateCount, 15);
      } else {
        counter.innerText = target + suffix;
      }
    };

    updateCount();
  });
}

const observerOptions = {
  root: null,
  threshold: 0.3,
};

const statsSection = document.querySelector(".relative.z-20.py-12");
let counted = false;

const statsObserver = new IntersectionObserver((entries, observer) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting && !counted) {
      startCounters();
      counted = true;
      observer.unobserve(entry.target);
    }
  });
}, observerOptions);

if (statsSection) {
  statsObserver.observe(statsSection);
}

window.addEventListener("load", () => {
  document.body.classList.remove("is-loading");
});
