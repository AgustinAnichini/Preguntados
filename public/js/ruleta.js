document.addEventListener('DOMContentLoaded', function() {
    const categoriaImagenes = {
        geografia: '/public/img/geography.svg',
        historia: '/public/img/history.svg',
        deporte: '/public/img/deporte.svg',
        entretenimiento: '/public/img/entretenimiento.svg',
        arte: '/public/img/arte.svg'
    };

    for (const categoria in categoriaImagenes) {
        const categoriaDiv = document.getElementById(categoria);
        if (categoriaDiv) {
            const imgElement = document.createElement('img');
            imgElement.src = categoriaImagenes[categoria];
            imgElement.alt = categoria;

            categoriaDiv.appendChild(imgElement);
            break;
        }
    }
});
