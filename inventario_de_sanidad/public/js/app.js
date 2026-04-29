document.addEventListener("DOMContentLoaded", function () {
    let sidebar = document.querySelector('.sidebar');
    let linkTexts = document.querySelectorAll('.link-text');
    let btnNotifications = document.getElementById("btn-notifications");
    let notificationsList = document.getElementById("notifications-list");
    let userInfoToggle = document.getElementById("user-info-toggle");
    let logoutSection = document.getElementById("logout-section");

    initSidebarToggle(sidebar, linkTexts);
    initSubmenus();
    initNotifications(btnNotifications, notificationsList);
    initLogoutToggle(userInfoToggle, logoutSection);
    initActiveLinks();
});

// Controla la apertura y cierre del sidebar
function initSidebarToggle(sidebar, linkTexts) {
    // Maneja el clic en cualquier parte del documento
    function handleDocumentClick(e) {
        // Comprueba si el clic fue dentro del sidebar
        let clickedInsideSidebar = e.target.closest('.sidebar');
        let isSidebarExpanded = sidebar.classList.contains('expanded');

        if (clickedInsideSidebar && !isSidebarExpanded) {
            // Si se clicó dentro y el sidebar no está expandido, lo expande
            sidebar.classList.add('expanded');
        } else if (!clickedInsideSidebar && isSidebarExpanded) {
            // Si se clicó fuera y el sidebar está expandido, colapsa y oculta textos
            let i = 0;
            while (i < linkTexts.length) {
                linkTexts[i].classList.remove('show');
                i+=1;
            }

            sidebar.classList.remove('expanded');

            // Cierra cualquier submenú abierto
            let openItems = document.querySelectorAll('.has-submenu.open');
            let j = 0;
            while (j < openItems.length) {
                openItems[j].classList.remove('open');
                j+=1;
            }
        }
    }

    // Cuando termina la transición CSS de ancho, muestra los textos si está expandido
    function handleTransitionEnd(e) {
        if (e.propertyName === 'width' && sidebar.classList.contains('expanded')) {
            let i = 0;
            while (i < linkTexts.length) {
                linkTexts[i].classList.add('show');
                i+=1;
            }
        }
    }

    document.addEventListener('click', handleDocumentClick);
    sidebar.addEventListener('transitionend', handleTransitionEnd);
}

// Inicializa la apertura/cierre de submenús dentro del sidebar
function initSubmenus() {
    let submenuParents = document.querySelectorAll(".sidebar .has-submenu");
    let i = 0;

    while (i < submenuParents.length) {
        let parent = submenuParents[i];
        let toggleLink = parent.querySelector("a");
        if (toggleLink) {
            // Al hacer click en el enlace del submenú, evita la navegación y alterna la clase 'open'
            toggleLink.addEventListener("click", function (e) {
                e.preventDefault();
                parent.classList.toggle("open");
            });
        }

        i+=1;
    }
}

// Inicializa el botón de notificaciones para mostrar/ocultar la lista de notificaciones
function initNotifications(btnNotifications, notificationsList) {
    if (!btnNotifications || !notificationsList) {
        return;
    }

    // Función que alterna la visibilidad del listado de notificaciones
    function toggleNotifications(e) {
        e.stopPropagation();
        notificationsList.classList.toggle("show");
    }

    // Función que oculta el listado si el click fue fuera del botón o la lista
    function closeNotifications(e) {
        let isInsideBtn = e.target.closest("#btn-notifications");
        let isInsideList = e.target.closest("#notifications-list");
        if (!isInsideBtn && !isInsideList) {
            notificationsList.classList.remove("show");
        }
    }

    btnNotifications.addEventListener("click", toggleNotifications);
    document.addEventListener("click", closeNotifications);
}

// Inicializa el toggle para mostrar/ocultar la sección de logout en el menú de usuario
function initLogoutToggle(userInfoToggle, logoutSection) {
    // Alterna la visibilidad del logout
    function toggleLogout(e) {
        e.stopPropagation();
        logoutSection.style.display = (logoutSection.style.display === "none" || logoutSection.style.display === "")
            ? "block"
            : "none";
    }

    // Oculta logout si el click fue fuera del dropdown de usuario
    function hideLogout(e) {
        if (!e.target.closest(".user-dropdown")) {
            logoutSection.style.display = "none";
        }
    }

    userInfoToggle.addEventListener("click", toggleLogout);
    document.addEventListener("click", hideLogout);
}

// Marca como activo el link seleccionado dentro del sidebar
function initActiveLinks() {
    let links = document.querySelectorAll('.sidebar a');
    let i = 0;

    while (i < links.length) {
        let link = links[i];
        let href = link.getAttribute('href');

        if (href && href !== '') {
            // Al hacer click, remueve 'active' de todos y la añade al seleccionado
            link.addEventListener('click', function () {
                let j = 0;
                while (j < links.length) {
                    links[j].classList.remove('active');
                    j+=1;
                }
                this.classList.add('active');
            });
        }

        i+=1;
    }
}
