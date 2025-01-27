document.addEventListener('DOMContentLoaded', () => {
    // Select the SVG container
    const div = document.querySelector('.first-floor-svg');

    if (div) {
        let iframe = document.getElementById('roomData');
        // Hide iframe if src is empty
        if (iframe.src === '') {
            iframe.style.display = 'none';
        }

        // Select all SVG elements within the container
        const elements = div.querySelectorAll('*');

        elements.forEach(element => {
            const id = element.id;
            // Check if the ID starts with 'b' followed by exactly three digits
            if (id && /^b\d{3}$/.test(id)) { // e.g., b101, b102, ..., b115
                element.style.cursor = 'pointer';
                element.addEventListener('click', () => {
                    iframe.style.height = '200vh';
                    // Scroll to the iframe smoothly
                    iframe.scrollIntoView({ behavior: 'smooth' });
                    // Show loader
                    const loader = document.querySelector('.loader-container');
                    if (loader) {
                        loader.style.display = 'inline-block';
                    }
                    setTimeout(() => {
                        iframe.src = `room.php?room=${id}`;
                        iframe.style.display = 'block';
                    }, 100);
                    iframe.onload = () => {
                        if (loader) {
                            loader.style.display = 'none';
                        }
                        // Adjust iframe height based on content
                        let pcContent = iframe.contentWindow.document.querySelector('.pc-content');
                        if (pcContent) {
                            iframe.style.height = pcContent.scrollHeight + 'px';
                        }
                    };
                });

                // **Add Label to the Center of the Room**
                // Ensure the element is an SVG shape (e.g., <path>, <polygon>, etc.)
                if (element instanceof SVGGraphicsElement) {
                    // Get the bounding box of the element
                    const bbox = element.getBBox();
                    // Calculate center coordinates
                    const centerX = bbox.x + bbox.width / 2;
                    const centerY = bbox.y + bbox.height / 2;

                    // Create a new SVG text element
                    const svgNS = "http://www.w3.org/2000/svg";
                    const text = document.createElementNS(svgNS, 'text');
                    text.setAttribute('x', centerX);
                    text.setAttribute('y', centerY);
                    text.setAttribute('class', 'room-label');
                    text.textContent = id.toUpperCase(); // Display as 'B101', 'B102', etc.
                    // set click event on text
                    text.addEventListener('click', () => {
                        iframe.style.height = '200vh';
                        // Scroll to the iframe smoothly
                        iframe.scrollIntoView({ behavior: 'smooth' });
                        // Show loader
                        const loader = document.querySelector('.loader-container');
                        if (loader) {
                            loader.style.display = 'inline-block';
                        }
                        setTimeout(() => {
                            iframe.src = `room.php?room=${id}`;
                            iframe.style.display = 'block';
                        }, 100);
                        iframe.onload = () => {
                            if (loader) {
                                loader.style.display = 'none';
                            }
                            // Adjust iframe height based on content
                            let pcContent = iframe.contentWindow.document.querySelector('.pc-content');
                            if (pcContent) {
                                iframe.style.height = pcContent.scrollHeight + 'px';
                            }
                        };
                    });

                    // Append the text to the SVG
                    // Option 1: Append to the group to maintain hierarchical structure
                    element.parentNode.appendChild(text);

                    // Option 2: Alternatively, append to the main SVG if desired
                    // const svg = div.querySelector('svg');
                    // if (svg) {
                    //     svg.appendChild(text);
                    // }
                }
            }
        });
    }

    // Handle "Go to Top" button functionality
    const goTopButton = document.querySelector('.pc-go-top');
    if (goTopButton) {
        goTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Show or hide the "Go to Top" button based on scroll position
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                goTopButton.style.display = 'block';
            } else {
                goTopButton.style.display = 'none';
            }
        });
    }
});
