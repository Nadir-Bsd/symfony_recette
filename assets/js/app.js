// Loader
window.addEventListener('load', () => {
    const loader = document.querySelector('.loader-wrapper');
    loader.style.opacity = '0';
    setTimeout(() => loader.style.display = 'none', 500);
});

// Thème sombre
const themeToggle = document.querySelector('.theme-toggle');
themeToggle?.addEventListener('click', () => {
    document.documentElement.setAttribute('data-theme', 
        document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'
    );
});

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
