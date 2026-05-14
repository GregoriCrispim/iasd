document.addEventListener("DOMContentLoaded", () => {
    const script = document.createElement('script');
    script.src = "https://cdnjs.cloudflare.com/ajax/libs/suncalc/1.8.0/suncalc.min.js";
    script.onload = iniciarCalculoPorDoSol;
    document.head.appendChild(script);
});

function iniciarCalculoPorDoSol() {
    let latitude = -23.5505;
    let longitude = -46.6333;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, fallback);
    } else {
        fallback();
    }

    function success(position) {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        atualizarPorDoSol();
    }

    function fallback() {
        atualizarPorDoSol();
    }

    function atualizarPorDoSol() {
        const hoje = new Date();
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        const times = SunCalc.getTimes(hoje, latitude, longitude);
        const sunsetTime = times.sunset;

        const horaLocal = sunsetTime.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit',
            timeZone: timezone
        });

        const dia = String(hoje.getDate()).padStart(2, '0');
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');

        const horarioElement = document.getElementById("horario");
        if (horarioElement) {
            horarioElement.textContent = horaLocal;
        }

        const dataElement = document.getElementById("data");
        if (dataElement) {
            dataElement.textContent = `${dia}/${mes}`;
        }
    }
}