const imageGalleryContainer = document.querySelector('.image-gallery-container');

document.addEventListener('openGallery', () => {
    imageGalleryContainer.classList.add('active');
});

imageGalleryContainer?.addEventListener('click', async (event) => {
    console.log('click in gallery');

    try {
        const endpoint = document.querySelector('.upload-image-input').dataset.endpoint

        const result = await fetch(`${endpoint}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        })

        const data = await result.json()
        console.log(data)
        document.getElementById('tab-images').innerHTML = data.imageGallery
    } catch (error) {
        console.error(error)
    }

    if (event.target.closest('.close')) {
        imageGalleryContainer.classList.remove('active');
    }

    if (event.target.closest('.upload-image')) {
        document.querySelector('.upload-image-input').click()
    }

    if (event.target.closest('.delete-button')) {
        const endpoint = event.target.closest('.delete-button').dataset.endpoint

        const result = await fetch(`${endpoint}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        })

        const data = await result.json()
        console.log(data)
        document.getElementById('tab-images').innerHTML = data.imageGallery
    }
})

document.querySelector('.upload-image-input').addEventListener('change', async (event) => {
    console.log('upload image')
    try {
        const endpoint = document.querySelector('.upload-image-input').dataset.endpoint
        const image = event.target.files[0]

        const formData = new FormData()
        formData.append('image', image)

        const result = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })

        const data = await result.json()
        document.getElementById('tab-images').innerHTML = data.imageGallery

    } catch (error) {
        console.error(error)
    }
})