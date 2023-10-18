document.addEventListener('DOMContentLoaded', function () {
    const thumbnails = document.querySelectorAll('.preview.ea-lightbox-thumbnail');

    if(thumbnails.length) {
        console.log(thumbnails);
        thumbnails.forEach(function (thumbnail) {
            thumbnail.addEventListener('click', function (event) {
                event.preventDefault();
                const lightboxId = this.getAttribute('data-ea-lightbox-content-selector');
                const lightbox = document.querySelector(lightboxId);

                lightbox.style.display = 'block';
            });
        });

        const closeButtons = document.querySelectorAll('.close-lightbox');

        closeButtons.forEach(function (closeButton) {
            closeButton.addEventListener('click', function () {
                const lightbox = this.closest('.ea-lightbox');
                lightbox.style.display = 'none';
            });
        });
    }
});