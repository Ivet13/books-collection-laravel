        <!-- Tabs -->
        <div class="tab">
            <button class="tablinks active" data-tab="General">General</button>
            <button class="tablinks" data-tab="Languages">Languages</button>

            <!-- Tab content -->
            <div id="General" class="tabcontent">
                <h3>General</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.</p>
            </div>

            <div id="Languages" class="tabcontent" style="display: none;">
                <!-- Tab links -->
                <div class="tab">
                    <button class="tablinks" data-tab="EN">EN</button>
                    <button class="tablinks" data-tab="ES">ES</button>


                    <!-- Tab content -->
                    <div id="EN" class="tabcontent" style="display: block;">
                        <h3>EN</h3>
                        <div>
                            <label for="name">Name</label>
                            <input name="name" value="{{ old('name', $author->name ?? '') }}">
                        </div>

                        <div style="width:100%;">
                            <label for="bio">Biography</label>
                            <textarea name="bio">{{ old('bio', $author->bio ?? '') }}</textarea>
                        </div>
                    </div>

                    <div id="ES" class="tabcontent" style="display: none;">
                        <h3>ES</h3>
                        <div>
                            <label for="name">Nombre</label>
                            <input name="name" value="{{ old('name', $author->name ?? '') }}">
                        </div>

                        <div style="width:100%;">
                            <label for="bio">Biografía</label>
                            <textarea name="bio">{{ old('bio', $author->bio ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
