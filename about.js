/* About Page Animations */

document.addEventListener('DOMContentLoaded', () => {
    const aboutHero = document.querySelector('.about-hero');
    const bottomRow = document.querySelector('.bottom-row');
    const socialBar = document.querySelector('.social-bar');

    // Initial states
    if (aboutHero) {
        aboutHero.style.opacity = '0';
        aboutHero.style.transform = 'translateY(30px)';
        aboutHero.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
    }

    if (bottomRow) {
        const boxes = bottomRow.children;
        for (let box of boxes) {
            box.style.opacity = '0';
            box.style.transform = 'translateY(30px)';
            box.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        }
    }

    if (socialBar) {
        socialBar.style.opacity = '0';
        socialBar.style.transform = 'translateY(-20px)';
        socialBar.style.transition = 'all 0.6s ease-out';
    }

    // Intersection Observer for scroll animations
    const aboutObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target === aboutHero) {
                    aboutHero.style.opacity = '1';
                    aboutHero.style.transform = 'translateY(0)';
                } else if (entry.target === bottomRow) {
                    const boxes = bottomRow.children;
                    for (let i = 0; i < boxes.length; i++) {
                        setTimeout(() => {
                            boxes[i].style.opacity = '1';
                            boxes[i].style.transform = 'translateY(0)';
                        }, i * 200);
                    }
                } else if (entry.target === socialBar) {
                    socialBar.style.opacity = '1';
                    socialBar.style.transform = 'translateY(0)';
                }
                aboutObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    if (aboutHero) aboutObserver.observe(aboutHero);
    if (bottomRow) aboutObserver.observe(bottomRow);
    if (socialBar) aboutObserver.observe(socialBar);
});
