<!-- Tabs -->
<div class="tab">
    <button class="tablinks active" data-tab="tab-general">Subir nuevo archivo</button>

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





</div>
