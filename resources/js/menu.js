document.addEventListener("DOMContentLoaded", () => {

    const toggle = document.getElementById("menuToggle");
    const menu = document.getElementById("sideMenu");
    const overlay = document.getElementById("menuOverlay");

    if (!toggle || !menu || !overlay) return;

    const openMenu = () => {
        document.body.classList.add("menu-open");
        overlay.hidden = false;
        toggle.setAttribute("aria-expanded", "true");
        menu.setAttribute("aria-hidden", "false");
    };

    const closeMenu = () => {
        document.body.classList.remove("menu-open");
        toggle.setAttribute("aria-expanded", "false");
        menu.setAttribute("aria-hidden", "true");
        window.setTimeout(() => (overlay.hidden = true), 250);
    };

    toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        document.body.classList.contains("menu-open") ? closeMenu() : openMenu();
    });

    overlay.addEventListener("click", closeMenu);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && document.body.classList.contains("menu-open")) {
            closeMenu();
        }
    });
});
