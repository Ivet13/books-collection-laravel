<!-- Tabs -->
<div class="tab">
    <button class="tablinks active" data-tab="tab-general">Subir nuevo archivo</button>
    <button class="tablinks" data-tab="tab-images">Ver galería</button>

    <!-- Tab content -->
    <div id="tab-general" class="tabcontent">
        <h3>Subir</h3>

        <div class="form-group">
            <label for="file">Archivo</label>
            <input type="file" name="file" id="file" class="upload-image-input"
                data-endpoint="{{ route('images_store') }}">
        </div>
        <button class="modal-confirm">Subir</button>

    </div>

    <!-- Tab content -->
    <div id="tab-images" class="tabcontent image-gallery-container" style="display: none;">

        <h3>Images</h3>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.</p>

    </div>



</div>
