document.addEventListener("DOMContentLoaded", function() {
    const timerDisplay = document.getElementById("timer");
    let timeLeft = timerDisplay.textContent; // 30 seconds

    timerDisplay.textContent = timeLeft;

    const countdown = setInterval(function() {
        timeLeft--;
        timerDisplay.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            window.location.href = '/partida/finDelJuego';
        }
    }, 1000);
});
