// Toggle Dark Mode
document.addEventListener("DOMContentLoaded", () => {
  const toggleBtn = document.getElementById("toggle-dark-mode");
  toggleBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    toggleBtn.textContent = document.body.classList.contains("dark-mode") ? "☀️" : "🌙";
  });

  // Filtrage par race (si présent sur la page)
  const raceFilter = document.getElementById("race-filter");
  if (raceFilter) {
    raceFilter.addEventListener("change", () => {
      const selectedRace = raceFilter.value;
      const dogs = document.querySelectorAll(".dog");
      dogs.forEach((dog) => {
        if (selectedRace === "all" || dog.dataset.race === selectedRace) {
          dog.style.display = "block";
        } else {
          dog.style.display = "none";
        }
      });
    });
  }
});
