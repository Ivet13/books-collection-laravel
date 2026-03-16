
document.addEventListener("click", async (e) => {
    const langSelector = e.target.closest(".lang-select-container");
    if (!langSelector) return;
    e.preventDefault();


    try {

        let lang = e.target.value;

        const formData = new FormData();
        formData.append('lang', lang);
        formData.append('path', window.location.href);

        console.log(lang, window.location.href);

        const response = await fetch('/change-language', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData,

        })

        if (response.status === 500) {
            throw response
        }

        const json = await response.json();

        window.location.replace(json.route);


    } catch (err) {

        console.error("CLICK HANDLER ERROR:", err);
    }

});

