document.addEventListener('DOMContentLoaded', function() {
  // Inicialización de la gráfica de metas
  const ctx = document.getElementById('goalsChart').getContext('2d');
  const goalsChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Meta 1', 'Meta 2', 'Meta 3', 'Meta 4', 'Meta 5'],
      datasets: [{
        label: 'Metas',
        data: [12, 19, 3, 5, 2],
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      }
    }
  });

  // Inicialización de la gráfica de fondos
  const ctx2 = document.getElementById('fundsChart').getContext('2d');
  const fundsChart = new Chart(ctx2, {
    type: 'pie',
    data: {
      labels: ['Fondos A', 'Fondos B', 'Fondos C'],
      datasets: [{
        label: 'Fondos',
        data: [30, 40, 30],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)'
        ],
        borderWidth: 1
      }]
    }
  });
});