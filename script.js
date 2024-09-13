document.getElementById('search').addEventListener('input', function(e) {
    const filter = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.patient-card');
    
    cards.forEach(card => {
        const name = card.querySelector('p').textContent.toLowerCase();
        if (name.includes(filter)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});