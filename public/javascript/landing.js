document.addEventListener("DOMContentLoaded", function () {
    // Trigger staggered animation for each card
    const cards = document.querySelectorAll('.work-card');
    cards.forEach(card => {
        // Force reflow to ensure delay works
        void card.offsetWidth;
        card.classList.add('animate');
    });
});