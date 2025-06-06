document.addEventListener("DOMContentLoaded", () => {
  let startTime = Date.now();
  let totalTime = 0;

  async function sendTime(duration) {
    fetch("/include/visit_duration.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `category=${encodeURIComponent(
        pageCategory
      )}&duration=${encodeURIComponent(duration)}`,
    });
  }

  let sent = false;

  async function pauseTimer() {
    if (!startTime || sent) return;
    const now = Date.now();
    const duration = Math.ceil((now - startTime) / 1000 / 60);

    if (duration > 0) {
      sendTime(duration);
      sent = true;
    }
    startTime = null;
  }

  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      pauseTimer();
    } else {
      startTime = Date.now();
    }
  });

  window.addEventListener("beforeunload", () => {
    if (startTime) pauseTimer();
  });

  const logoutBtn = document.getElementById("btn-deconnexion");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      pauseTimer();
    });
  }
});
