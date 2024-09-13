// Search Functionality for Patient List Page
const searchInput = document.getElementById('search');
if (searchInput) {
    searchInput.addEventListener('input', function (e) {
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
}

// Button Functionality for Appointment Management
const newAppointmentBtn = document.querySelector('.new-appointment');
const rescheduleBtn = document.querySelector('.reschedule');

if (newAppointmentBtn) {
    newAppointmentBtn.addEventListener('click', function () {
        alert('New appointment scheduled');
    });
}

if (rescheduleBtn) {
    rescheduleBtn.addEventListener('click', function () {
        alert('Appointment rescheduled');
    });
}
