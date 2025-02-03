// Scroll reveal
const scrollReveal = () => {
    const elements = document.querySelectorAll('.scroll-reveal');
    elements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top <= window.innerHeight * 0.8) {
            el.classList.add('visible');
        }
    });
};

window.addEventListener('scroll', scrollReveal);
scrollReveal();
