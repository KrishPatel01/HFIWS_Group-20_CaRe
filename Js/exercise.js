document.querySelector('.submit-btn').addEventListener('click', (e) => {
    e.preventDefault();  

    const exerciseDate = document.getElementById('exercise-date').value;
    const exerciseType = document.getElementById('exercise-type').value;
    const exerciseDuration = document.getElementById('exercise-duration').value;
    const exerciseIntensity = document.getElementById('exercise-intensity').value;
    const caloriesBurned = document.getElementById('calories-burned').value;
    const exerciseMood = document.getElementById('exercise-mood').value;
    const exerciseGoals = document.getElementById('exercise-goals').value;
    const exerciseNotes = document.getElementById('exercise-notes').value;
    const shareWithTherapist = document.getElementById('share').checked;
    const fileUpload = document.getElementById('file-upload').files[0];

    const formData = new FormData();
    formData.append('exerciseDate', exerciseDate);
    formData.append('exerciseType', exerciseType);
    formData.append('exerciseDuration', exerciseDuration);
    formData.append('exerciseIntensity', exerciseIntensity);
    formData.append('caloriesBurned', caloriesBurned);
    formData.append('exerciseMood', exerciseMood);
    formData.append('exerciseGoals', exerciseGoals);
    formData.append('exerciseNotes', exerciseNotes);
    formData.append('shareWithTherapist', shareWithTherapist);
    if (fileUpload) {
        formData.append('file', fileUpload);  

    fetch('submit-exercise-log-endpoint', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            alert('Exercise log submitted successfully!');
        } else {
            alert('There was an issue submitting your exercise log.');
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting your exercise log.');
    });
}});
