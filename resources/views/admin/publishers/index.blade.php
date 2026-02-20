<x-admin-layout title="Editoriales | Admin">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="publishers-page">
        <aside class="publishers-left">
            <form class="js-filter-form" method="GET" action="{{ route('admin.publishers.index') }}">
                <div class="search-box">
                    <input type="text" name="q" placeholder="Buscar editorial..." value="{{ request('q') }}">
                    <button type="submit">Buscar</button>
                </div>
            </form>

            {{-- lista + paginación --}}
            <div class="js-list">
                @forelse ($records as $record)
                    <x-publisher-item :publisher="$record" />
                @empty
                    <p>No hay editoriales.</p>
                @endforelse

                <div class="js-pagination">
                    {{ $records->links() }}
                </div>
            </div>
        </aside>

        <main class="publishers-right">
            <x-publisher-form />
        </main>
    </div>

    @push('scripts')
        <style>
            .publishers-page {
                display: grid;
                grid-template-columns: 1fr 420px;
                gap: 16px;
            }

            .edit-tab {
                cursor: pointer;
            }

            .edit-tab.selected {
                outline: 2px solid blue;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const listContainer = document.querySelector('.js-list');
                const form = document.querySelector('.js-publisher-form');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const errorsDiv = form.querySelector('.js-errors');
                const deleteBtn = form.querySelector('.delete-btn');
                const resetBtn = form.querySelector('.js-publisher-reset');

                // Helper: Mostrar errores
                function showErrors(errors) {
                    errorsDiv.innerHTML = '';
                    if (!errors) return;
                    // Laravel devuelve obj: { field: ["error..."], ... }
                    Object.values(errors).flat().forEach(msg => {
                        const p = document.createElement('p');
                        p.textContent = msg;
                        errorsDiv.appendChild(p);
                    });
                }

                // Helper: Limpiar form
                function resetForm() {
                    form.reset();
                    document.getElementById('publisher_id').value = '';
                    document.getElementById('method').value = 'POST';
                    deleteBtn.style.display = 'none';
                    errorsDiv.innerHTML = '';

                    // Quitar selección visual
                    document.querySelectorAll('.edit-tab.selected').forEach(el => el.classList.remove('selected'));
                }

                // SUBMIT (Create / Update)
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    showErrors(null);

                    const id = document.getElementById('publisher_id').value;
                    const isUpdate = !!id;
                    /*
                     * Si es update, url = admin/publishers/{id}  (PUT)
                     * Si es create, url = admin/publishers      (POST)
                     */
                    const urlBase = form.dataset.showUrlBase; // .../admin/publishers
                    const url = isUpdate ? `${urlBase}/${id}` : form.dataset.storeUrl;
                    const method = isUpdate ? 'PUT' : 'POST';

                    const payload = {
                        name: form.name.value,
                        bio: form.bio.value
                    };

                    try {
                        const resp = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await resp.json();

                        if (!resp.ok) {
                            if (resp.status === 422) {
                                showErrors(data.errors);
                            } else {
                                alert('Error: ' + (data.message || resp.statusText));
                            }
                            return;
                        }

                        // Éxito -> Recargar lista (simple reload por ahora, o fetch parcial)
                        window.location.reload();
                    } catch (err) {
                        console.error(err);
                        alert('Error de red');
                    }
                });

                // CLICK en item de la lista -> Cargar en form (EDIT Mode)
                listContainer.addEventListener('click', async (e) => {
                    const card = e.target.closest('.edit-tab');
                    if (!card) return;

                    // Marcar visualmente
                    document.querySelectorAll('.edit-tab.selected').forEach(el => el.classList.remove(
                        'selected'));
                    card.classList.add('selected');

                    const id = card.dataset.id;
                    const urlBase = form.dataset.showUrlBase;
                    // GET /admin/publishers/{id} -> devuelve JSON
                    try {
                        const resp = await fetch(`${urlBase}/${id}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        if (!resp.ok) throw new Error('No se pudo cargar');
                        const data = await resp.json();

                        // Llenar form
                        document.getElementById('publisher_id').value = data.id;
                        document.getElementById('method').value =
                            'PUT'; // para lógica interna si hiciera falta
                        form.name.value = data.name;
                        form.bio.value = data.bio || '';

                        deleteBtn.style.display = 'inline-block';

                        // Configurar delete logic
                        deleteBtn.onclick = async () => {
                            if (!confirm('¿Seguro de eliminar?')) return;
                            try {
                                const respDel = await fetch(`${urlBase}/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json'
                                    }
                                });
                                if (respDel.ok) {
                                    window.location.reload();
                                } else {
                                    alert('No se pudo eliminar');
                                }
                            } catch (err) {
                                console.error(err);
                            }
                        };

                    } catch (err) {
                        console.error(err);
                    }
                });

                // RESET
                resetBtn.addEventListener('click', resetForm);
            });
        </script>
    @endpush
</x-admin-layout>
