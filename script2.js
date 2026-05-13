const container = document.getElementById('container');
const signUpBtn = document.getElementById('signUpBtn');
const signInBtn = document.getElementById('signInBtn');

if (signUpBtn && signInBtn && container) {
    signUpBtn.addEventListener('click', (e) => {
        e.preventDefault();
        container.classList.add("active");
    });

    signInBtn.addEventListener('click', (e) => {
        e.preventDefault();
        container.classList.remove("active");
    });
}

// "Pizzazz" - mouse move parallax for background blobs
document.addEventListener('mousemove', (e) => {
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;

    const blobs = document.querySelectorAll('.blob');
    blobs.forEach((blob, index) => {
        const speed = (index + 1) * 20;
        blob.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
    });
});

// Scroll-down entrance animation for form elements
window.addEventListener('load', () => {
    const reveals = document.querySelectorAll('.form-header, .input-group, .btn-primary, .btn-google, .form-footer');
    reveals.forEach((el, index) => {
        el.classList.add('reveal');
        setTimeout(() => {
            el.classList.add('active');
        }, 500 + (index * 100));
    });
});