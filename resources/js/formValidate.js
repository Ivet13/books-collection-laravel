
document.addEventListener("DOMContentLoaded", () => {
    if (event.target.closest('.save-button')) {
        const saveButton = event.target.closest('.save-button');
        const form = formContainer.querySelector('form');
        const formData = new FormData(form);
        const endpoint = saveButton.dataset.endpoint;

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })

            if (!response.ok) {
                throw response;
            }

            const data = await response.json();

            console.log(data)


        } catch (err) {
            if (err.status === 422) {
                const errors = await err.json();

                Object.entries(errors.errors).forEach(([key, value]) => {
                    const input = form.querySelector([name = "${key}"])
                    console.log(key)
                    const label = input.parentElement.querySelector('label');

                    if (label) {
                        console.log(label)
                        const error = document.createElement('span');
                        error.classList.add('error');
                        error.textContent = value;
                        console.log(value)
                        label.appendChild(error);
                    }
                });

                console.log(errors);
            } else {
                console.error('Otro error', err);
            }
        }
    }
});