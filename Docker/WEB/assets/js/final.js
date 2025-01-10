const div = document.querySelector('.first-floor-svg');

if (div) {
    const elements = div.querySelectorAll('*');

    elements.forEach(element => {
        const id = element.id;
        if (id && id.startsWith('b1')) {
            element.style.cursor = 'pointer';
            element.addEventListener('click', () => {
                window.location.href = `room.php?room=${id}`;
            });
        }
    });
}
