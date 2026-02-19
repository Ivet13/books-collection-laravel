document.addEventListener("click", async (e) => {
    const link = e.target.closest(".side-menu a");
    if (!link) return;

    e.preventDefault();

    const url = link.href;
    console.log(url)
    const res = await fetch(url, {
        headers: {
            "Accept": "text/html",
        },
    });

    if (!res.ok) {
        console.error("Error cargando", res.status);
        return;
    }

    const html = await res.text();
    const tmp = document.createElement("div");
    tmp.innerHTML = html;
    console.log(html)

    const newContent = tmp.querySelector("#app-content");
    const appContent = document.querySelector("#app-content");

    if (newContent && appContent) {
        appContent.innerHTML = newContent.innerHTML;
        //   window.history.pushState({}, "", url);
    } else {
        console.error("No encuentro #app-content en la respuesta");
    }
});
