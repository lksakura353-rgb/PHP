/* TechNova Contact Page Logic */

document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Subtle feedback
            submitBtn.textContent = 'SENDING...';
            submitBtn.style.opacity = '0.7';
            submitBtn.disabled = true;

            setTimeout(() => {
                submitBtn.textContent = 'MESSAGE SENT!';
                submitBtn.style.background = '#00ff00';
                submitBtn.style.color = '#000';
                
                // Clear form
                contactForm.reset();

                setTimeout(() => {
                    submitBtn.textContent = 'SEND MESSAGE';
                    submitBtn.style.background = '';
                    submitBtn.style.color = '';
                    submitBtn.style.opacity = '1';
                    submitBtn.disabled = false;
                }, 3000);
            }, 1500);
        });
    }

    // Scroll reveal for cards
    const infoCards = document.querySelectorAll('.info-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    infoCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease-out';
        observer.observe(card);
    });
});
