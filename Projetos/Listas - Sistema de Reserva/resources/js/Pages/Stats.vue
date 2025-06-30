<script setup>
import QueueStatsChart from '@/Components/Queue/QueueStatsChart.vue';
import Navbar from '@/Components/Navbar.vue';
import { ref } from 'vue';

const chartKey = ref(0); // ← adicione isso
</script>

<template>
  <Navbar />
<div class="card mt-5 p-6">
  <div class="card-header">Estatísticas e Métricas</div>
  <div class="card-body">
    <QueueStatsChart :key="chartKey" />

  </div>
</div>
</template>
