     @props(['author'])
     <!-- Tabs -->
     <div class="tab">
         <button class="tablinks active" data-tab="General">General</button>
         <button class="tablinks tab-images-button image-gallery-container" data-endpoint="{{ route('images_index') }}"
             data-upload-endpoint="{{ route('images_store') }}" data-entity-type="author"
             data-entity-id="{{ $author->id ?? '' }}" data-tab="Images">Images</button>

         <!-- Tab content -->
         <div id="General" class="tabcontent">
             <h3>General</h3>
             <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.</p>
             <div id="Languages" class="tabcontent">
                 <!-- Tab links -->
                 <div class="tab">
                     <button class="tablinks" data-tab="EN">EN</button>
                     <button class="tablinks" data-tab="ES">ES</button>


                     <!-- Tab content -->
                     <div id="EN" class="tabcontent" style="display: block;">
                         <h3>EN</h3>
                         <div>
                             <label for="name">Name</label>
                             <input name="locale[en][name]"
                                 value="{{ data_get($author, 'locale.en.name', $author->name ?? '') }}">
                         </div>

                         <div style="width:100%;">
                             <label for="bio">Biography</label>
                             <textarea name="locale[en][bio]">{{ data_get($author, 'locale.en.bio', $author->bio ?? '') }}</textarea>
                             <img data-original-filename="{{ $author->images[$locale]['lg']['poster'][0]['originalFilename'] }}"
                                 srcset="
        {{ route('image', ['entity' => 'authors', 'entityId' => $author->id, 'filename' => $author->images[$locale]['xs']['poster'][0]['filename']]) }} 480w,
        {{ route('image', ['entity' => 'authors', 'entityId' => $author->id, 'filename' => $author->images[$locale]['sm']['poster'][0]['filename']]) }} 768w,
        {{ route('image', ['entity' => 'authors', 'entityId' => $author->id, 'filename' => $author->images[$locale]['md']['poster'][0]['filename']]) }} 1024w,
        {{ route('image', ['entity' => 'authors', 'entityId' => $author->id, 'filename' => $author->images[$locale]['lg']['poster'][0]['filename']]) }} 1440w
      "
                                 sizes="
        (max-width: 480px) 480px,
        (max-width: 768px) 768px,
        (max-width: 1024px) 1024px,
        1440px
      "
                                 src="{{ route('image', ['entity' => 'courses', 'entityId' => $course->id, 'filename' => $course->images[$locale]['lg']['poster'][0]['filename']]) }}"
                                 alt="{{ $course->images[$locale]['lg']['poster'][0]['alt'] }}"
                                 title="{{ $course->images[$locale]['lg']['poster'][0]['title'] }}">
                         </div>

                         <div id="ES" class="tabcontent" style="display: none;">
                             <h3>ES</h3>
                             <div>
                                 <label for="name_es">Nombre</label>
                                 <input name="locale[es][name]" value="{{ data_get($author, 'locale.es.name', '') }}">
                             </div>

                             <div style="width:100%;">
                                 <label for="bio_es">Biografía</label>
                                 <textarea name="locale[es][bio]">{{ data_get($author, 'locale.es.bio', '') }}</textarea>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Tab content -->
             <div id="Images" class="tabcontent tab-images" style="display: none;">
                 <h3>Images</h3>
                 <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.</p>
             </div>
         </div>
