<template>
  <div class="container py-4">
    <div class="card shadow rounded p-4">
      <h2 class="mb-4 text-center">Estatísticas da Fila de Hoje</h2>

      <!-- Gráfico -->
      <div class="mb-5" v-if="barData.labels.length">
        <div style="height: 300px;">
          <Bar :data="barData" :options="barOptions" />
        </div>
      </div>

      <!-- Estatísticas resumidas -->
      <div class="row g-4 text-center justify-content-center p-4 rounded">
        <!-- Atração Mais Popular -->
        <div class="col-12 col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Atração Mais Popular</h5>
              <p class="card-text fw-bold">
                {{ stats.atracao_mais_popular?.attraction_name || '—' }}
              </p>
              <small class="text-muted">
                {{ stats.atracao_mais_popular?.total || 0 }} reservas
              </small>
            </div>
          </div>
        </div>

        <!-- Visitante Mais Ativo -->
        <div class="col-12 col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Visitante Mais Ativo</h5>
              <p class="card-text fw-bold">
                {{ stats.visitante_mais_ativo?.visitor_name || '—' }}
              </p>
              <small class="text-muted">
                {{ stats.visitante_mais_ativo?.visitor_email }}
              </small><br>
              <small class="text-muted">
                {{ stats.visitante_mais_ativo?.total || 0 }} reservas
              </small>
            </div>
          </div>
        </div>

        <!-- Total de Reservas -->
        <div class="col-12 col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Total de Reservas</h5>
              <p class="card-text fs-4 text-primary">{{ totalReservas }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend
} from 'chart.js';

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const stats = ref({
  reservas_por_hora: [],
  atracao_mais_popular: null,
  visitante_mais_ativo: null
});

const barData = ref({
  labels: [],
  datasets: [{
    label: 'Reservas por Hora',
    data: [],
    backgroundColor: '#4e73df'
  }]
});

const barOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: { beginAtZero: true }
  }
};

const totalReservas = computed(() =>
  stats.value.reservas_por_hora.reduce((sum, item) => sum + item.total, 0)
);

onMounted(async () => {
  try {
    const response = await axios.get('/fila/estatisticas');
    stats.value = response.data;

    const reservas = response.data.reservas_por_hora;

    if (reservas.length > 0) {
      barData.value.labels = reservas.map(item => `${item.hora}h`);
      barData.value.datasets[0].data = reservas.map(item => item.total);
    }
  } catch (error) {
    console.error('Erro ao carregar estatísticas:', error);
  }
});
</script>


<style scoped>
.chart-canvas {
  max-width: 90%;
  height: 300px;
}

.card {
  background-color: #fff;
  border: none;
}

h2 {
  font-weight: 600;
  color: #333;
}
.card-title {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}
</style>
