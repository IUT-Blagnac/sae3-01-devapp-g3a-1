const div = document.querySelector('.first-floor-svg');

if (div) {

    let iframe = document.getElementById('roomData');
    // display none iframe when src is empty
    if (iframe.src === '') {
        iframe.style.display = 'none';
    }

    const elements = div.querySelectorAll('*');

    elements.forEach(element => {
        const id = element.id;
        if (id && id.startsWith('b1')) {
            element.style.cursor = 'pointer';
            element.addEventListener('click', () => {
                iframe.style.height = '200vh';
                // empty iframe src
                document.getElementById('roomData').scrollIntoView();
                document.getElementById('roomData').scrollIntoView({behavior: 'smooth'});
                document.querySelector('.loader-container').style.display = 'inline-block';
                setTimeout(() => {
                    iframe.src = `room.php?room=${id}`;
                    iframe.style.display = 'block';
                }, 1000);
                iframe.onload = () => {
                    document.querySelector('.loader-container').style.display = 'none';
                    // adapt iframe height to iframe .pc-content height
                    let pcContent = iframe.contentWindow.document.querySelector('.pc-content');
                    if (pcContent) {
                        iframe.style.height = pcContent.scrollHeight + 'px';
                    }
                };
            });
        }
    });
}

// .pc-go-top on click
document.querySelector('.pc-go-top').addEventListener('click', () => {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});

// show .pc-go-top when scrolling down
window.onscroll = function () {
    let goTop = document.querySelector('.pc-go-top');
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        goTop.style.display = 'block';
    } else {
        goTop.style.display = 'none';
    }
};


