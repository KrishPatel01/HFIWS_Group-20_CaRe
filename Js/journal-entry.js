// Handle emotion selection and toggle the green color when clicked
const emotionButtons = document.querySelectorAll('.emotion-btn');

emotionButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove the 'selected' class from all buttons
        emotionButtons.forEach(btn => btn.classList.remove('selected'));
        
        // Add the 'selected' class to the clicked button
        button.classList.add('selected');
    });
});
