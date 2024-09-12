const form = document.querySelector('.login-form');
form.addEventListener('submit', function (e) {
    e.preventDefault(); 
    const username = form.querySelector('input[type="text"]').value;
    const password = form.querySelector('input[type="password"]').value;

    if (username === "" || password === "") {
        alert("Please fill in all fields.");
    } else {
        
        alert("Login successful!");
    }
});
