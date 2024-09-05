document.addEventListener('DOMContentLoaded', function() {
  // Inicialización de la gráfica de metas
  
  fetch('metas/home')
    .then(response => response.json())
    .then(data => {
        // Aquí puedes trabajar con los datos recibidos (por ejemplo, mostrarlos en la consola)
        console.log(data);
       
    })
    .catch(error => {
        console.error('Error al obtener datos:', error);
    });


    fetch('metas/home')
    .then(response => response.json())
    .then(data => {
      console.log(data);
        const labels = data.map(item => item.nombreProyecto);
        const meta = data.map(item => item.meta);
        const Vactual = data.map(item => item.valorActual);
        const ctx = document.getElementById('goalsChart').getContext('2d');
  
        const goalsChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Metas',
              data: meta,
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
            labels: labels,
            datasets: [{
              label: 'Fondos Actuales',
              data: Vactual,
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

      })
      .catch(error => console.error('Error al obtener datos:', error));


 
});