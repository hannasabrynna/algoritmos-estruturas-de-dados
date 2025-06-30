<script setup>
import QueueStatsChart from '@/Components/Queue/QueueStatsChart.vue';
import Navbar from '@/Components/Navbar.vue';
import { ref } from 'vue';

const chartKey = ref(0);
</script>

<template>
  <Navbar />

  <main class="p-6">
    <h1 class="text-2xl font-bold mb-4">Estatísticas e Métricas</h1>
    <section class="w-full h-full flex items-center justify-center rounded-lg card mt-5 p-6">
      <div class="card-body w-full h-full bg-white p-8">
        <QueueStatsChart :key="chartKey" />
      </div>
    </section>
  </main>
</template>
