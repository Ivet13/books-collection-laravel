<div class="modal modify-image-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Modificar imagen</h2>
            <button class="modal-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <title>window-close</title>
                    <path
                        d="M13.46,12L19,17.54V19H17.54L12,13.46L6.46,19H5V17.54L10.54,12L5,6.46V5H6.46L12,10.54L17.54,5H19V6.46L13.46,12Z" />
                </svg></button>
        </div>
        <div class="modal-body form-content">
            <button class="modify-button" data>MODIFY</button>
            <input type="hidden" name="configuration" id="configuration"
                value="{{ json_encode([
                    'thumbnail' => [
                        'widthPx' => '100',
                        'heightPx' => '100',
                    ],
                    'xs' => [
                        'widthPx' => '200',
                        'heightPx' => '200',
                    ],
                    'sm' => [
                        'widthPx' => '200',
                        'heightPx' => '200',
                    ],
                    'md' => [
                        'widthPx' => '450',
                        'heightPx' => '450',
                    ],
                    'lg' => [
                        'widthPx' => '450',
                        'heightPx' => '450',
                    ],
                ]) }}">
            <input type="text" name="name" id="name" placeholder="name">
            <input type="text" name="alt" id="alt" placeholder="Alt">
            <input type="text" name="title" id="title" placeholder="Title">

        </div>
        <div class="modal-footer">

            <button class="modal-cancel">Cancelar</button>
        </div>
    </div>
</div>
