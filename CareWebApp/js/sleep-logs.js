// scripts.js

document.querySelector('.submit-btn').addEventListener('click', (e) => {
    e.preventDefault();  // Prevent the form from submitting the traditional way

    const sleepDate = document.getElementById('sleep-date').value;
    const bedtime = document.getElementById('bedtime').value;
    const wakeTime = document.getElementById('wake-time').value;
    const sleepQuality = document.getElementById('sleep-quality').value;
    const interruptions = document.getElementById('interruptions').value;
    const wakingMood = document.getElementById('waking-mood').value;
    const dreams = document.getElementById('dreams').value;
    const sleepAids = document.getElementById('sleep-aids').value;
    const shareWithTherapist = document.getElementById('share').checked;
    const fileUpload = document.getElementById('file-upload').files[0];

    // Create form data to send to the server
    const formData = new FormData();
    formData.append('sleepDate', sleepDate);
    formData.append('bedtime', bedtime);
    formData.append('wakeTime', wakeTime);
    formData.append('sleepQuality', sleepQuality);
    formData.append('interruptions', interruptions);
    formData.append('wakingMood', wakingMood);
    formData.append('dreams', dreams);
    formData.append('sleepAids', sleepAids);
    formData.append('shareWithTherapist', shareWithTherapist);
    if (fileUpload) {
        formData.append('file', fileUpload);  // Append the file if there is one
    }

    // Send form data to the server
    fetch('submit-sleep-log-endpoint', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            alert('Sleep log submitted successfully!');
        } else {
            alert('There was an issue submitting your sleep log.');
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting your sleep log.');
    });
});
