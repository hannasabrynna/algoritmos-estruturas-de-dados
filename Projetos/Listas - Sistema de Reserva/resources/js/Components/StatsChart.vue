<!-- resources/js/Pages/StatsChart.vue -->
<template>
  <div class="bg-white rounded-xl shadow p-6 w-full max-w-3xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">Reservas por Dia</h2>
    <Bar v-if="loaded" :data="chartData" :options="chartOptions" />
    <p v-else class="text-center text-gray-500">Carregando dados...</p>
  </div>
</template>

<script setup>
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

import { ref, onMounted } from 'vue'
import axios from 'axios'

const chartData = ref({
  labels: [],
  datasets: [{
    label: 'Reservas por dia',
    backgroundColor: '#3b82f6',
    data: []
  }]
})

const chartOptions = {
  responsive: true,
  plugins: {
    legend: { position: 'top' },
    title: { display: false }
  }
}

const loaded = ref(false)

onMounted(async () => {
  try {
    const response = await axios.get('/stats/reservas-por-dia')
    const dados = response.data

    chartData.value.labels = dados.map(item => item.data)
    chartData.value.datasets[0].data = dados.map(item => item.total)
    loaded.value = true
  } catch (error) {
    console.error('Erro ao buscar dados da API:', error)
  }
})
</script>
