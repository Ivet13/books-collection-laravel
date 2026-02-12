
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.main-content form');
            if (!form) return; // Guard clause just in case

            const submitBtn = form.querySelector('button[type="submit"]');
            const resetBtn = form.querySelector('button[type="reset"]');
            const defaultAction = "{{ route('admin.books.store') }}";
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';

            // Function to reset form to create mode
            function resetForm() {
                form.action = defaultAction;
                if (form.contains(methodInput)) {
                    form.removeChild(methodInput);
                }
                form.reset();
                // Clear values manually in case they were set via JS
                document.getElementById('title').value = '';
                document.getElementById('isbn').value = '';
                document.getElementById('description').value = '';

                // Remove selected class from all items if exists
                document.querySelectorAll('.edit-tab').forEach(el => el.classList.remove('selected'));
            }

            resetBtn.addEventListener('click', function(e) {
                // Allow default reset but ensure we clean up extra stuff
                setTimeout(resetForm, 0);
            });

            document.querySelectorAll('.edit-tab').forEach(item => {
                item.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const url = `/admin/books/${id}`;

                    // Fetch book data
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            // Populate form
                            document.getElementById('title').value = data.title || '';
                            document.getElementById('isbn').value = data.isbn || '';
                            document.getElementById('description').value = data.description ||
                                '';

                            // Update form action
                            form.action = url;

                            // Add _method PUT
                            if (!form.contains(methodInput)) {
                                form.appendChild(methodInput);
                            }

                            // Visual feedback (optional)
                            document.querySelectorAll('.edit-tab').forEach(el => el.classList
                                .remove('selected'));
                            this.classList.add('selected');
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });