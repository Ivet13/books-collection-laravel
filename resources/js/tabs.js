document.addEventListener("DOMContentLoaded", () => {

    document.addEventListener("click", (e) => {
        const tabLink = e.target.closest(".tablinks");
        if (!tabLink) return;

        e.preventDefault();

        // Get direct children elements of the parent of the clicked tab with class="tabcontent" and hide them
        let tabcontents = [...tabLink.parentElement.children].filter(element => element.classList.contains("tabcontent"));
        tabcontents.forEach(element => {
            //add display:none inline style
            element.style.display = "none";
        });

        // Show the current tab, and add an "active" class to the button that opened the tab        
        document.getElementById(tabLink.dataset.tab).style.display = "block";
        tabLink.classList.add("active");

    });
});