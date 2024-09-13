document.getElementById('affirmation-type').addEventListener('change', function() {
    const customAffirmationInput = document.getElementById('custom-affirmation');
    if (this.value === 'custom') {
        customAffirmationInput.classList.remove('hidden');  
    } else {
        customAffirmationInput.classList.add('hidden');  
    }
});

document.querySelector('.submit-btn').addEventListener('click', (e) => {
    e.preventDefault();  

    const affirmationDate = document.getElementById('affirmation-date').value;
    const affirmationType = document.getElementById('affirmation-type').value === 'custom' 
        ? document.getElementById('custom-affirmation').value 
        : document.getElementById('affirmation-type').value;
    const personalReflection = document.getElementById('personal-reflection').value;
    const moodBefore = document.getElementById('mood-before').value;
    const moodAfter = document.getElementById('mood-after').value;
    const affirmationSource = document.getElementById('affirmation-source').value;
    const affirmationReminder = document.getElementById('affirmation-reminder').value;
    const shareWithTherapist = document.getElementById('share').checked;
    const fileUpload = document.getElementById('file-upload').files[0];

    const formData = new FormData();
    formData.append('affirmationDate', affirmationDate);
    formData.append('affirmationType', affirmationType);
    formData.append('personalReflection', personalReflection);
    formData.append('moodBefore', moodBefore);
    formData.append('moodAfter', moodAfter);
    formData.append('affirmationSource', affirmationSource);
    formData.append('affirmationReminder', affirmationReminder);
    formData.append('shareWithTherapist', shareWithTherapist);
    if (fileUpload) {
        formData.append('file', fileUpload);  
    }

    fetch('submit-affirmation-log-endpoint', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            alert('Affirmation log submitted successfully!');
        } else {
            alert('There was an issue submitting your affirmation log.');
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting your affirmation log.');
    });
});
