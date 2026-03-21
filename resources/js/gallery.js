const csrf = document.head.querySelector('meta[name="csrf-token"]').content;

//
// CARGAR GALERÍA — delegated so it works even when form is injected dynamically
//
document.addEventListener('click', async (event) => {

    const tabImagesButton = event.target.closest('.tab-images-button');
    if (tabImagesButton) {
        const tabImages = document.querySelector('.tab-images');
        let endpoint  = tabImagesButton.dataset.endpoint;
        const entityType = tabImagesButton.dataset.entityType;
        const entityId   = tabImagesButton.dataset.entityId;

        const url = new URL(endpoint, window.location.origin);
        if (entityType) url.searchParams.append('entity_type', entityType);
        if (entityId) url.searchParams.append('entity_id', entityId);

        const res  = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
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
        modal.dataset.id       = modifyBtn.dataset.id;
        
        const altInput     = document.querySelector('#alt');
        const captionInput = document.querySelector('#caption');
        
        if (altInput) altInput.value         = modifyBtn.dataset.alt || '';
        if (captionInput) captionInput.value = modifyBtn.dataset.caption || '';

        modal.classList.add('active');
        return;
    }

    // ELIMINIAR IMAGEN
    const deleteBtn = event.target.closest('.delete-button');
    if (deleteBtn) {
        event.preventDefault();
        const endpoint = deleteBtn.dataset.endpoint;

        const res  = await fetch(endpoint, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();

        const tabImages = document.querySelector('.tab-images');
        if (tabImages) tabImages.innerHTML = data.imageGallery;
        return;
    }

    // SUBIR IMAGEN — abrir file picker
    if (event.target.closest('.upload-image')) {
        const uploadInput = document.getElementById('uploadFileInput');
        if (!uploadInput) return;

        // Propagate entity info from the tab-images-button into the input
        const tabImagesBtn = document.querySelector('.tab-images-button');
        if (tabImagesBtn) {
            uploadInput.dataset.entityType = tabImagesBtn.dataset.entityType;
            uploadInput.dataset.entityId   = tabImagesBtn.dataset.entityId;
            uploadInput.dataset.endpoint   = tabImagesBtn.dataset.uploadEndpoint;
        }

        uploadInput.click();
        return;
    }

    // UPLOAD MODAL — cerrar
    const uploadModal = document.querySelector('.modal.upload-modal');
    if (uploadModal) {
        if (event.target.closest('.upload-modal .modal-close') ||
            event.target.closest('.upload-modal .modal-cancel')) {
            uploadModal.classList.remove('active');
            return;
        }
    }

    // MODIFY MODAL — cerrar
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
            const id       = modal.dataset.id;
            const alt      = document.querySelector('#alt')?.value ?? '';
            const caption  = document.querySelector('#caption')?.value ?? '';

            const res = await fetch(endpoint, {
                method: 'PUT',
                headers: {
                    'Accept':       'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ id, alt, caption })
            });

            const data = await res.json();
            const tabImages = document.querySelector('.tab-images');
            if (tabImages) tabImages.innerHTML = data.imageGallery;

            if (document.querySelector('#alt'))     document.querySelector('#alt').value = '';
            if (document.querySelector('#caption')) document.querySelector('#caption').value = '';

            modal.classList.remove('active');
            return;
        }
    }
});

//
// SUBIR IMAGEN (file input change)
//
document.addEventListener('change', async (event) => {
    const uploadInput = event.target.closest('#uploadFileInput');
    if (!uploadInput) return;

    try {
        const endpoint   = uploadInput.dataset.endpoint;
        const entityType = uploadInput.dataset.entityType;
        const entityId   = uploadInput.dataset.entityId;
        const image      = uploadInput.files[0];

        if (!image || !endpoint) return;

        const formData = new FormData();
        formData.append('image',       image);
        formData.append('entity_type', entityType);
        formData.append('entity_id',   entityId);

        const res  = await fetch(endpoint, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: formData
        });

        const data = await res.json();
        const tabImages = document.querySelector('.tab-images');
        if (tabImages) tabImages.innerHTML = data.imageGallery;

        // Close upload modal after successful upload
        const uploadModal = document.querySelector('.modal.upload-modal');
        if (uploadModal) uploadModal.classList.remove('active');

        // Reset file input
        uploadInput.value = '';

    } catch (error) {
        console.error('Upload error:', error);
    }
});