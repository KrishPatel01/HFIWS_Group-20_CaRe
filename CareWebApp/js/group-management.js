const patients = document.querySelectorAll('.patient-group li');
const groups = document.querySelectorAll('.patient-group');

patients.forEach(patient => {
    patient.addEventListener('dragstart', dragStart);
});

groups.forEach(group => {
    group.addEventListener('dragover', dragOver);
    group.addEventListener('drop', dropPatient);
});

function dragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.id);
}

function dragOver(e) {
    e.preventDefault();
}

function dropPatient(e) {
    const id = e.dataTransfer.getData('text');
    const patient = document.getElementById(id);
    e.target.appendChild(patient);
}
