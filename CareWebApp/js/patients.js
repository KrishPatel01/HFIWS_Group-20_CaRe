// scripts.js


document.getElementById('add-activity').addEventListener('click', function() {
    window.location.href = 'add-activity.html';  // Redirect to the new page
});

// Mood Chart
const moodChart = document.getElementById('moodChart').getContext('2d');
new Chart(moodChart, {
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Mood Levels',
            data: [3, 4, 2, 5, 4, 3, 5],
            borderColor: '#4CAF50',
            backgroundColor: 'rgba(76, 175, 80, 0.2)',
            fill: true,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 5
            }
        }
    }
});

// Sleep Chart
const sleepChart = document.getElementById('sleepChart').getContext('2d');
new Chart(sleepChart, {
    type: 'bar',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Hours of Sleep',
            data: [7, 6, 8, 7, 5, 7, 8],
            backgroundColor: '#00308F',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 10
            }
        }
    }
});
