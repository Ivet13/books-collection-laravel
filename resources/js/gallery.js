const gallery = document.querySelector('.image-gallery-container');
const tabImages = document.querySelector('.tab-images');
const uploadInput = document.querySelector('.upload-image-input');
const csrf = document.head.querySelector('meta[name="csrf-token"]').content;

// CLICK GENERAL (delegación)
gallery?.addEventListener('click', async (event) => {


    gallery?.classList.add('active');

    const endpoint = gallery.dataset.endpoint;

    const res = await fetch(endpoint);
    const data = await res.json();

    tabImages.innerHTML = data.imageGallery;


    // CERRAR
    if (event.target.closest('.close')) {
        gallery.classList.remove('active');
    }

    // SUBIR IMAGEN (abrir input)
    if (event.target.closest('.upload-image')) {
        uploadInput.click();
    }

    // ELIMINAR
    if (event.target.closest('.delete-button')) {
        event.preventDefault();

        const endpoint = event.target.closest('.delete-button').dataset.endpoint;

        const res = await fetch(endpoint, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });

        const data = await res.json();
        tabImages.innerHTML = data.imageGallery;
    }

    // MODAL MODIFICAR
    if (event.target.closest('.js-crud-modify')) {
        const modal = document.querySelector('.modify-image-modal');
        modal.classList.add('active');
    }
});

// MODAL (cerrar)
const modal = document.querySelector('.modify-image-modal');

modal?.addEventListener('click', (event) => {
    if (
        event.target.closest('.modal-close') ||
        event.target.closest('.modal-cancel')
    ) {
        modal.classList.remove('active');
    }
});

// MODIFICAR
modal?.addEventListener('click', async (event) => {
    if (event.target.closest('.modify-button')) {
        event.preventDefault();

        const endpoint =
            event.target.closest('.modify-button').dataset.endpoint;

        console.log('modify:', endpoint);
    }
});

// SUBIR IMAGEN
uploadInput?.addEventListener('change', async (event) => {
    try {
        const endpoint = uploadInput.dataset.endpoint;
        const image = event.target.files[0];

        const formData = new FormData();
        formData.append('image', image);

        const res = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: formData
        });

        const data = await res.json();
        tabImages.innerHTML = data.imageGallery;

    } catch (error) {
        console.error(error);
    }
});