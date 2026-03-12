     @props(['publisher'])
     <!-- Tabs -->
     <div class="tab">
         <button class="tablinks active" data-tab="General">General</button>
         <button class="tablinks" data-tab="Images">Images</button>

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
                             <input name="locale[en.name]" value="{{ old('name', $publisher->name ?? '') }}">
                         </div>

                         <div style="width:100%;">
                             <label for="bio">Biography</label>
                             <textarea name="locale[en.bio]">{{ old('bio', $publisher->bio ?? '') }}</textarea>
                         </div>
                     </div>

                     <div id="ES" class="tabcontent" style="display: none;">
                         <h3>ES</h3>
                         <div>
                             <label for="name_es">Nombre</label>
                             <input name="locale[es.name]" value="{{ old('name', $publisher->name ?? '') }}">
                         </div>

                         <div style="width:100%;">
                             <label for="bio_es">Biografía</label>
                             <textarea name="locale[es.bio]">{{ old('bio', $publisher->bio ?? '') }}</textarea>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         <!-- Tab content -->
         <div id="Images" class="tabcontent" style="display: none;">
             <h3>Images</h3>
             <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.</p>
             <div id="Images" class="tabcontent" style="display: none;">

             </div>
         </div>



     </div>
