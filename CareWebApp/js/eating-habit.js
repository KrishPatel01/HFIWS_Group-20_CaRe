document.querySelector('.submit-btn').addEventListener('click', (e) => {
    e.preventDefault();  // Prevent the form from submitting traditionally

    const eatingDate = document.getElementById('eating-date').value;
    const mealType = document.getElementById('meal-type').value;
    const foodDescription = document.getElementById('food-description').value;
    const portionSize = document.getElementById('portion-size').value;
    const waterIntake = document.getElementById('water-intake').value;
    const eatingMood = document.getElementById('eating-mood').value;
    const cravings = document.getElementById('cravings').value;
    const emotionalEating = document.getElementById('emotional-eating').value;
    const shareWithTherapist = document.getElementById('share').checked;
    const fileUpload = document.getElementById('file-upload').files[0];

    // Create form data to send to the server
    const formData = new FormData();
    formData.append('eatingDate', eatingDate);
    formData.append('mealType', mealType);
    formData.append('foodDescription', foodDescription);
    formData.append('portionSize', portionSize);
    formData.append('waterIntake', waterIntake);
    formData.append('eatingMood', eatingMood);
    formData.append('cravings', cravings);
    formData.append('emotionalEating', emotionalEating);
    formData.append('shareWithTherapist', shareWithTherapist);
    if (fileUpload) {
        formData.append('file', fileUpload);  // Append file if uploaded
    }

    // Send form data to the server
    fetch('submit-eating-habits-endpoint', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            alert('Eating habits log submitted successfully!');
        } else {
            alert('There was an issue submitting your eating habits log.');
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting your eating habits log.');
    });
});
