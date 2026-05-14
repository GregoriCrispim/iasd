document.addEventListener("DOMContentLoaded", () => {
    const instagramUrl = "https://www.instagram.com/iasdbrasilia/";
    const botoes = document.querySelectorAll(".btn_canais button");
    const divs = [
        document.getElementById("div1"),
        document.getElementById("div2"),
    ];

    function mostrarDiv(index) {
        divs.forEach((div, i) => {
            div.style.display = i === index ? "block" : "none";
        });

        botoes.forEach((btn, i) => {
            btn.classList.toggle("ativo", i === index);
        });
    }

    botoes.forEach((btn, i) => {
        btn.addEventListener("click", (e) => {
            if (btn.id === "btn2") {
                e.preventDefault();
                e.stopPropagation();
                window.open(instagramUrl, "_blank", "noopener,noreferrer");
                return;
            }

            mostrarDiv(i);
        });
    });

    mostrarDiv(0);
});