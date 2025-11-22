const showMenu = (headerToggle, navbarId) => {
  const toggleBtn = document.getElementById(headerToggle),
    nav = document.getElementById(navbarId);

  // Validate that variables exist
  if (toggleBtn && nav) {
    toggleBtn.addEventListener("click", () => {
      // We add the show-menu class to the div tag with the nav-menu class
      nav.classList.toggle("show-menu");
      // change icon
      toggleBtn.classList.toggle("bx-x");
    });
  }
};
showMenu("header-toggle", "navbar");

const linkColor = document.querySelectorAll(
  ".nav-link:not(.nav-dropdown .nav-link)"
);

function colorLink() {
  linkColor.forEach((l) => l.classList.remove("active"));
  this.classList.add("active");
}

linkColor.forEach((l) => l.addEventListener("click", colorLink));

/*==================== DROPDOWN CLICK ====================*/
document.addEventListener("DOMContentLoaded", function () {
  const dropdowns = document.querySelectorAll(".nav-dropdown");
  const navbar = document.getElementById("navbar");

  dropdowns.forEach((dropdown) => {
    const dropdownLink = dropdown.querySelector(".nav-link");
    if (dropdownLink) {
      dropdownLink.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Abrir sidebar se não estiver expandido
        if (!navbar.classList.contains("nav-expanded")) {
          navbar.classList.add("nav-expanded");
        }

        // Toggle dropdown
        dropdown.classList.toggle("show-dropdown");
        console.log("Dropdown clicked!");
      });
    }
  });

  // Fechar dropdown quando sidebar fechar
  const navLogo = navbar?.querySelector(".nav-logo");
  if (navLogo && navbar) {
    navLogo.addEventListener("click", function (e) {
      e.preventDefault();
      const isExpanded = navbar.classList.contains("nav-expanded");

      // Se estiver fechando o sidebar, fechar todos os dropdowns
      if (isExpanded) {
        dropdowns.forEach((dropdown) => {
          dropdown.classList.remove("show-dropdown");
        });
      }

      navbar.classList.toggle("nav-expanded");
    });
  }
});

/*==================== TOGGLE DARK MODE ====================*/
window.toggleDarkMode = function () {
  const html = document.documentElement;
  const currentTheme = html.getAttribute("data-theme");
  const isDark = currentTheme === "dark";

  const themeIconNav = document.getElementById("theme-icon-nav");
  const themeTextNav = document.getElementById("theme-text-nav");

  if (isDark) {
    html.removeAttribute("data-theme");
    localStorage.setItem("theme", "light");
    if (themeIconNav) {
      themeIconNav.className = "bx bx-sun nav-icon";
    }
    if (themeTextNav) {
      themeTextNav.textContent = "Modo Claro";
    }
  } else {
    html.setAttribute("data-theme", "dark");
    localStorage.setItem("theme", "dark");
    if (themeIconNav) {
      themeIconNav.className = "bx bx-moon nav-icon";
    }
    if (themeTextNav) {
      themeTextNav.textContent = "Modo Escuro";
    }
  }
};

// Inicializar tema ao carregar
document.addEventListener("DOMContentLoaded", function () {
  const savedTheme = localStorage.getItem("theme");
  const themeIconNav = document.getElementById("theme-icon-nav");
  const themeTextNav = document.getElementById("theme-text-nav");

  if (savedTheme === "dark") {
    document.documentElement.setAttribute("data-theme", "dark");
    if (themeIconNav) {
      themeIconNav.className = "bx bx-moon nav-icon";
    }
    if (themeTextNav) {
      themeTextNav.textContent = "Modo Escuro";
    }
  }
});
