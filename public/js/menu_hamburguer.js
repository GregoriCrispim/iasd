function toggleMenu() {
    var menu = document.querySelector(".navegation");
    var menuIcon = document.getElementById("menu-icon");
    var closeIcon = document.getElementById("close-icon");
    var header = document.querySelector("header");

    menu.classList.toggle("active");

    if (menu.classList.contains("active")) {
        menuIcon.style.display = "none";
        closeIcon.style.display = "flex";
        const headerHeight = header.offsetHeight;
        menu.style.top = `${headerHeight}px`;
    } else {
        menuIcon.style.display = "flex";
        closeIcon.style.display = "none";
    }
}

const header = document.querySelector("header");
const menu = document.querySelector(".navegation");

const observer = new ResizeObserver(() => {
    if (menu.classList.contains("active")) {
        const headerHeight = header.offsetHeight;
        menu.style.top = `${headerHeight}px`;
    }
});

observer.observe(header);

//Drop Down
const btn_drop = document.querySelectorAll('.btn_drop');
const dropdown = document.querySelectorAll('.dropdown');

btn_drop.forEach((btn, index) => {
    // Pega todos os links dentro do btn_drop
    const allLinks = btn.querySelectorAll('a');
    // O primeiro link é sempre o link principal (não está dentro do dropdown)
    const linkPrincipal = allLinks[0];
    // Os links dentro do dropdown começam a partir do segundo
    const linksDropdown = Array.from(allLinks).slice(1);
    
    if (linkPrincipal) {
        linkPrincipal.addEventListener('click', (e) => {
            // Mobile: dropdowns sempre abertos, não faz toggle
            if (window.matchMedia && window.matchMedia('(max-width: 800px)').matches) {
                return;
            }

            // Se o href for "#", sempre faz toggle do dropdown
            if (linkPrincipal.getAttribute('href') === '#') {
                e.preventDefault();
                e.stopPropagation();
                dropdown[index].classList.toggle('ativo');
                // Fecha outros dropdowns
                dropdown.forEach((dd, i) => {
                    if (i !== index) dd.classList.remove('ativo');
                });
                return;
            }
            
            // Se o clique foi na seta (ícone) ou próximo dela, toggle o dropdown
            // Caso contrário, permite que o link redirecione normalmente
            const target = e.target;
            const isIcon = target.tagName === 'I' || target.closest('i');
            const isChevron = isIcon && (target.classList.contains('bx-chevron-down') || target.closest('.bx-chevron-down'));
            
            if (isChevron || e.offsetX > linkPrincipal.offsetWidth - 30) {
                // Clique na seta ou perto da seta - toggle dropdown
                e.preventDefault();
                e.stopPropagation();
                dropdown[index].classList.toggle('ativo');
            } else {
                // Clique no texto do link - permite redirecionamento normal
                // Fecha outros dropdowns antes de redirecionar
                dropdown.forEach((dd, i) => {
                    if (i !== index) dd.classList.remove('ativo');
                });
                // Não previne default, então o link funciona normalmente
            }
        });
    }
    
    // Garante que os links dentro do dropdown funcionem normalmente
    linksDropdown.forEach(link => {
        link.addEventListener('click', (e) => {
            // Permite que o link funcione normalmente, não previne o default
            e.stopPropagation();
            // Fecha todos os dropdowns ao clicar em um link do dropdown
            dropdown.forEach(dd => dd.classList.remove('ativo'));
        });
    });
});
