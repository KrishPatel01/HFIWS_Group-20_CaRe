// Example: Smooth scroll to the top of the page when 'Get Started' button is clicked
document.querySelector('.btn-primary').addEventListener('click', function (event) {
    event.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
  