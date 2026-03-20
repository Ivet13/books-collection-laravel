const gallery = document.querySelector('.image-gallery-container');
const tabImages = document.querySelector('.tab-images');
const uploadInput = document.querySelector('.upload-image-input');
const modal = document.querySelector('.modify-image-modal');

const csrf = document.head.querySelector('meta[name="csrf-token"]').content;

//
// GALERÍA (abrir + cargar imágenes + acciones)
//
gallery?.addEventListener('click', async (event) => {

    gallery.classList.add('active');

    const endpoint = gallery.dataset.endpoint;

    const res = await fetch(endpoint);
    const data = await res.json();

    tabImages.innerHTML = data.imageGallery;

    // CERRAR GALERÍA
    if (event.target.closest('.close')) {
        gallery.classList.remove('active');
        return;
    }

    // SUBIR IMAGEN (abrir input)
    if (event.target.closest('.upload-image')) {
        uploadInput.click();
        return;
    }

    // ELIMINAR IMAGEN
    const deleteBtn = event.target.closest('.delete-button');
    if (deleteBtn) {
        event.preventDefault();

        const endpoint = deleteBtn.dataset.endpoint;

        const res = await fetch(endpoint, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });

        const data = await res.json();
        tabImages.innerHTML = data.imageGallery;

        return;
    }
});

//
// ABRIR MODAL (desde la galería)
//
tabImages.addEventListener('click', (event) => {
    const button = event.target.closest('.js-crud-modify');

    if (button) {
        // Guardar datos en la modal
        modal.dataset.endpoint = button.dataset.endpoint;
        modal.dataset.id = button.dataset.id;

        modal.classList.add('active');
    }
});

//
// MODAL (cerrar + modificar)
//
modal?.addEventListener('click', async (event) => {

    // CERRAR
    if (
        event.target.closest('.modal-close') ||
        event.target.closest('.modal-cancel')
    ) {
        modal.classList.remove('active');
        return;
    }

    // MODIFICAR
    if (event.target.closest('.modify-button')) {

        const endpoint = modal.dataset.endpoint;
        const id = modal.dataset.id;

        const alt = document.querySelector('#alt').value;
        const caption = document.querySelector('#caption').value;

        console.log('modify:', endpoint, id, alt, caption);

        const res = await fetch(endpoint, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                id,
                alt,
                caption
            })
        });

        const data = await res.json();
        tabImages.innerHTML = data.imageGallery;

        document.querySelector('#alt').value = '';
        document.querySelector('#caption').value = '';

        modal.classList.remove('active');
    }
});

//
// SUBIR IMAGEN
//
uploadInput?.addEventListener('change', async (event) => {
    try {
        const endpoint = uploadInput.dataset.endpoint;
        const image = event.target.files[0];

        const formData = new FormData();
        formData.append('image', image);
        formData.append('entity_type', uploadInput.dataset.entityType);
        formData.append('entity_id', uploadInput.dataset.entityId);

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