document.querySelector('.submit-btn').addEventListener('click', (e) => {
    e.preventDefault();  

    const goalType = document.getElementById('goal-type').value;
    const goalDescription = document.getElementById('goal-description').value;
    const goalProgress = document.getElementById('goal-progress').value;
    const goalDeadline = document.getElementById('goal-deadline').value;
    const nextWeekNotes = document.getElementById('next-week-notes').value;
    const fileUpload = document.getElementById('file-upload').files[0];
    const shareWithTherapist = document.getElementById('share').checked;

    const formData = new FormData();
    formData.append('goalType', goalType);
    formData.append('goalDescription', goalDescription);
    formData.append('goalProgress', goalProgress);
    formData.append('goalDeadline', goalDeadline);
    formData.append('nextWeekNotes', nextWeekNotes);
    formData.append('shareWithTherapist', shareWithTherapist);
    if (fileUpload) {
        formData.append('file', fileUpload);  
    }

    fetch('submit-goals-endpoint', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            alert('Goals submitted successfully!');
        } else {
            alert('Error submitting your goals.');
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting your goals.');
    });
});
