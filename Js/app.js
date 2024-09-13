function searchPatients() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const patients = document.querySelectorAll('#patient-list li');
    
    patients.forEach(patient => {
        const name = patient.textContent.toLowerCase();
        if (name.includes(searchTerm)) {
            patient.style.display = 'flex';
        } else {
            patient.style.display = 'none';
        }
    });
}

function selectPatient(name) {
    document.getElementById('patient-name').textContent = name;
    window.location.href = 'view-notes.html'; // Redirect to the View Notes page
}

function saveNote() {
    alert('Note saved successfully!');
    window.location.href = 'notes.html'; // Redirect to the main Notes page
}
