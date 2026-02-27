// resources/js/navigation.js
async function loadCrud(url, { push = true } = {}) {
    const res = await fetch(url, { headers: { Accept: "application/json" } });
    if (!res.ok) throw new Error(`Error ${res.status}`);

    const data = await res.json(); // { form, table }
    document.querySelector("#crudForm").innerHTML = data.form ?? "";
    document.querySelector("#crudTable").innerHTML = data.table ?? "";

    if (push) history.pushState({}, "", url);
}

function isModifiedClick(e) {
    return e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0;
}

export function initNavigation() {
    document.addEventListener("click", (e) => {
        const link = e.target.closest(".side-menu a");
        if (!link) return;
        if (isModifiedClick(e)) return;

        e.preventDefault();
        loadCrud(link.href).catch(console.error);
    });

    window.addEventListener("popstate", () => {
        loadCrud(location.href, { push: false }).catch(console.error);
    });
}