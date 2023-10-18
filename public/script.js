const form = document.querySelector('form');
const meanElement = document.getElementById('mean');
const medianElement = document.getElementById('median');
const modeElement = document.getElementById('mode');
const upperLimitElement = document.getElementById('upperLimit');
const lowerLimitElement = document.getElementById('lowerLimit');
const zTableElement = document.getElementById('zTable');
const canvas = document.getElementById('myChart').getContext('2d');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = document.getElementById('data').value;
    const response = await fetch('/calculate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ data }),
    });
    const result = await response.json();
    meanElement.textContent = result.mean;
    medianElement.textContent = result.median;
    modeElement.textContent = result.mode;
    upperLimitElement.textContent = result.upperLimit;
    lowerLimitElement.textContent = result.lowerLimit;
    zTableElement.textContent = result.zTable;

    // Membuat grafik
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['Mean', 'Median', 'Modus', 'Upper Limit', 'Lower Limit', 'Z Table'],
            datasets: [{
                label: 'Statistik',
                data: [result.mean, result.median, result.mode, result.upperLimit, result.lowerLimit, result.zTable],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
