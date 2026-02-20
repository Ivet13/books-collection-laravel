 <form class="js-book-form" method="POST" action="{{ route('admin.books.store') }}"
     data-store-url="{{ route('admin.books.store') }}" data-show-url-base="{{ url('/admin/books') }}">
     @csrf

     {{-- Estado del formulario --}}
     <input type="hidden" name="book_id" id="book_id" value="">
     <input type="hidden" name="_method" id="method" value="POST"> {{-- lo usaremos para update/delete por AJAX --}}

     <header class="form-options">
         <div class="tabs">
             <button type="button">GENERAL</button>
         </div>

         <div class="buttons">
             <button type="submit" title="Guardar">
                 <span>
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                         <title>content-save</title>
                         <path
                             d="M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z" />
                     </svg>
                 </span>
             </button>

             <button type="reset" title="Limpiar">
                 <span>
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                         <title>broom</title>
                         <path
                             d="M19.36,2.72L20.78,4.14L15.06,9.85C16.13,11.39 16.28,13.24 15.38,14.44L9.06,8.12C10.26,7.22 12.11,7.37 13.65,8.44L19.36,2.72M5.93,17.57C3.92,15.56 2.69,13.16 2.35,10.92L7.23,8.83L14.67,16.27L12.58,21.15C10.34,20.81 7.94,19.58 5.93,17.57Z" />
                     </svg>
                 </span>

             </button>

             <button type="button" title="Eliminar" class="delete-btn" style="display:none;">
                 <span>
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                         <title>delete</title>
                         <path fill="currentColor"
                             d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
                     </svg>
                 </span>

             </button>
         </div>
     </header>

     {{-- ERRORES AJAX --}}
     <div class="js-errors" style="color:red; margin: 8px 0;"></div>

     <div class="form-fields">
         <div>
             <label for="title">Título</label>
             <input type="text" name="title" id="title" placeholder="El imperio del vampiro">
         </div>

         <div>
             <label for="isbn">ISBN</label>
             <input type="text" name="isbn" id="isbn" placeholder="978...">
         </div>

         <div>
             <label for="description">Sinopsis</label>
             <textarea name="description" id="description" placeholder="..."></textarea>
         </div>
     </div>

     {{-- INFO DEL LIBRO SELECCIONADO (NO inputs) --}}
     <section class="book-meta" style="margin-top: 16px;">
         <div>
             <strong>Autores:</strong>
             <div id="meta-authors">—</div>
         </div>

         <div style="margin-top: 8px;">
             <strong>Editorial:</strong>
             <span id="meta-publisher">—</span>
         </div>

         <div style="margin-top: 8px;">
             <strong>Año publicación:</strong>
             <span id="meta-year">—</span>
         </div>

         <div style="margin-top: 8px;">
             <strong>Genres:</strong>
             <div id="meta-genres">—</div>
         </div>
     </section>
 </form>
