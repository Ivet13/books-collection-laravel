const csrf = document.head.querySelector('meta[name="csrf-token"]').content;

//
// EVENTOS DE GALERÍA Y MODALES (DELEGADOS)
//
document.addEventListener('click', async (event) => {

    // CARGAR GALERÍA (desde el tab Images)
    const tabImagesButton = event.target.closest('.tab-images-button');
    if (tabImagesButton) {
        const tabImages = document.querySelector('.tab-images');
        let endpoint = tabImagesButton.dataset.endpoint;
        const entityType = tabImagesButton.dataset.entityType;
        const entityId = tabImagesButton.dataset.entityId;

        const url = new URL(endpoint, window.location.origin);
        if (entityType) url.searchParams.append('entity_type', entityType);
        if (entityId) url.searchParams.append('entity_id', entityId);

        const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
        const data = await res.json();

        if (tabImages) tabImages.innerHTML = data.imageGallery;
        return;
    }

    // ABRIR MODAL MODIFICAR desde la galería
    const modifyBtn = event.target.closest('.js-crud-modify');
    if (modifyBtn) {
        const modal = document.querySelector('.modify-image-modal');
        if (!modal) return;

        modal.dataset.endpoint = modifyBtn.dataset.endpoint;
        modal.dataset.id = modifyBtn.dataset.id;

        const altInput = document.querySelector('#alt');
        const nameInput = document.querySelector('#name');
        const titleInput = document.querySelector('#title');

        if (altInput) altInput.value = modifyBtn.dataset.alt || '';
        if (nameInput) nameInput.value = modifyBtn.dataset.name || '';
        if (titleInput) titleInput.value = modifyBtn.dataset.title || '';

        modal.classList.add('active');
        return;
    }

    // ELIMINIAR IMAGEN
    const deleteBtn = event.target.closest('.delete-button');
    if (deleteBtn) {
        event.preventDefault();
        const endpoint = deleteBtn.dataset.endpoint;

        const res = await fetch(endpoint, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();

        const tabImages = document.querySelector('.tab-images');
        if (tabImages) tabImages.innerHTML = data.imageGallery;
        return;
    }

    // UPLOAD MODAL — cerrar
    const uploadModal = document.querySelector('.modal.upload-modal');
    if (uploadModal) {
        if (event.target.closest('.upload-modal .modal-close') ||
            event.target.closest('.upload-modal .modal-cancel')) {
            uploadModal.classList.remove('active');

            // clear the file input when closing
            const fileInput = document.getElementById('file');
            if (fileInput) fileInput.value = '';

            return;
        }

        // SUBIR IMAGEN (click en boton "Subir")
        if (event.target.closest('.modal-confirm')) {
            const fileInput = document.getElementById('file');
            if (!fileInput) return;

            const endpoint = fileInput.dataset.endpoint;
            const entityType = fileInput.dataset.entityType;
            const entityId = fileInput.dataset.entityId;
            const image = fileInput.files[0];

            if (!image || !endpoint) return;

            const formData = new FormData();
            formData.append('image', image);
            formData.append('entity_type', entityType);
            formData.append('entity_id', entityId);

            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: formData
                });

                const data = await res.json();
                const tabImages = document.querySelector('.tab-images');
                if (tabImages) tabImages.innerHTML = data.imageGallery;

                // Reset and close
                fileInput.value = '';
                uploadModal.classList.remove('active');
            } catch (error) {
                console.error('Upload error:', error);
            }
            return;
        }
    }

    // MODIFY MODAL — cerrar y modificar
    const modal = document.querySelector('.modify-image-modal');
    if (modal) {
        if (event.target.closest('.modify-image-modal .modal-close') ||
            event.target.closest('.modify-image-modal .modal-cancel')) {
            modal.classList.remove('active');
            return;
        }

        // MODIFICAR
        if (event.target.closest('.modify-button')) {
            const endpoint = modal.dataset.endpoint;
            const id = modal.dataset.id;
            const alt = document.querySelector('#alt')?.value ?? '';
            const name = document.querySelector('#name')?.value ?? '';
            const title = document.querySelector('#title')?.value ?? '';
            const configuration = document.querySelector('#configuration')?.value ?? '';
            console.log(configuration);
            const res = await fetch(endpoint, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ id, alt, name, title, configuration })
            });

            const data = await res.json();
            const tabImages = document.querySelector('.tab-images');
            if (tabImages) tabImages.innerHTML = data.imageGallery;

            if (document.querySelector('#alt')) document.querySelector('#alt').value = '';
            if (document.querySelector('#name')) document.querySelector('#name').value = '';
            if (document.querySelector('#title')) document.querySelector('#title').value = '';
            if (document.querySelector('#configuration')) document.querySelector('#configuration').value = '';

            modal.classList.remove('active');
            return;
        }
    }
});