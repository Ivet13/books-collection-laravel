
document.addEventListener("click", (e) => {
    const langSelector = e.target.closest(".lang-select-container");
    if (!langSelector) return;
    e.preventDefault();
    console.log(langSelector);

});

