document.addEventListener("DOMContentLoaded", () => {
  const participerButtons = document.querySelectorAll(".participer-btn");
  const desinscrireButtons = document.querySelectorAll(".desinscrire-btn");

  participerButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const tournoiId = btn.getAttribute("data-id");
      fetch("/tournois/participation.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `id_tournoi=${tournoiId}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            location.reload();
          } else {
            alert("Erreur : " + data.message);
          }
        })
        .catch((error) => {
          console.error("Erreur lors de la participation:", error);
          alert("Une erreur s'est produite. Veuillez réessayer plus tard.");
        });
    });
  });

  desinscrireButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const tournoiId = btn.getAttribute("data-id");
      fetch("/tournois/desinscription.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `id_tournoi=${tournoiId}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            location.reload();
          } else {
            alert("Erreur : " + data.message);
          }
        })
        .catch((error) => {
          console.error("Erreur lors de la désinscription:", error);
          alert("Une erreur s'est produite. Veuillez réessayer plus tard.");
        });
    });
  });
});
