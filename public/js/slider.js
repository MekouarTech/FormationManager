let currentIndex = 0;
const slider = document.querySelector('.full-slider .slider');
const totalSlides = document.querySelectorAll('.full-slider .slider img').length;

function showSlide(index) {
    if (index >= totalSlides) currentIndex = 0;
    else if (index < 0) currentIndex = totalSlides - 1;
    else currentIndex = index;

    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
}

setInterval(() => {
    showSlide(currentIndex + 1);
}, 3000);

window.onload = () => showSlide(currentIndex);


// Initialize Performance metrics
  document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll(".counter");
    let started = false;

    const startCounting = () => {
      if (started) return;
      counters.forEach((counter) => {
        const target = +counter.getAttribute("data-target");
        let count = 0;

        const updateCounter = () => {
          const speed = 150;
          const increment = Math.ceil(target / speed);

          if (count < target) {
            count += increment;
            counter.textContent = count > target ? target : count;
            requestAnimationFrame(updateCounter);
          } else {
            counter.textContent = target;
          }
        };

        updateCounter();
      });

      started = true;
    };

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            startCounting();
          }
        });
      },
      { threshold: 0.5 }
    );

    const section = document.getElementById("performance");
    observer.observe(section);
  });