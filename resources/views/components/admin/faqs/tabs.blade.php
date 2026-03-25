     @props(['faq'])

     <div class="">
         <p>Faqs contents</p>
         <div id="Languages" class="">

             <!-- Tab links -->
             <div class="tab">
                 <button class="tablinks" data-tab="ES">ES</button>
                 <button class="tablinks" data-tab="EN">EN</button>

                 <!-- Tab content -->

                 <div id="ES" class="tabcontent" style="display: block;">
                     <h3>ES</h3>
                     <div>
                         <label for="locale[es][title]">Título</label>
                         <input name="locale[es][title]" value="{{ data_get($faq, 'locale.es.title', '') }}">
                     </div>

                     <div style="width:100%;">
                         <label for="locale[es][description]">Descripción</label>
                         <textarea name="locale[es][description]">{{ data_get($faq, 'locale.es.description', '') }}</textarea>
                     </div>
                 </div>

                 <div id="EN" class="tabcontent" style="display: none;">
                     <h3>EN</h3>
                     <div>
                         <label for="locale[en][title]">Title</label>
                         <input name="locale[en][title]"
                             value="{{ data_get($faq, 'locale.en.title', $faq->title ?? '') }}">
                     </div>

                     <div style="width:100%;">
                         <label for="locale[en][description]">Description</label>
                         <textarea name="locale[en][description]">{{ data_get($faq, 'locale.en.description', $faq->description ?? '') }}</textarea>
                     </div>
                 </div>

             </div>
         </div>
     </div>
