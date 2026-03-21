<div class="modal upload-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Galería</h2>
            <button class="modal-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <title>window-close</title>
                    <path
                        d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z" />
                </svg></button>
        </div>
        <div class="modal-body form-content">
            <x-tabs />
            {{-- Hidden file input used by gallery.js to trigger the OS file picker --}}
            <input type="file" id="uploadFileInput" class="upload-image-input" accept="image/*" style="display:none">
            {{-- Button visible to the user to start an upload --}}
            <button type="button" class="upload-image">Subir imagen</button>
        </div>
        <div class="modal-footer">
            <button class="modal-cancel">Cancelar</button>
        </div>
    </div>
</div>
