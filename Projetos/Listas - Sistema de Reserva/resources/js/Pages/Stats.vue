<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import StatsChart from '../Components/StatsChart.vue'
import Navbar from '@/Components/Navbar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';

const reservasPorDia = ref([])
const atracaoMaisDisputada = ref(null)
const visitanteMaisAtivo = ref(null)

onMounted(async () => {
  try {
    // Dados para gráfico
    const resReservas = await axios.get('/stats/reservas-por-dia')
    reservasPorDia.value = resReservas.data

    // Atração mais disputada
    const resAtracao = await axios.get('/stats/atracao-mais-disputada')
    atracaoMaisDisputada.value = resAtracao.data[0] || null

    // Visitante mais ativo
    const resVisitante = await axios.get('/stats/visitante-mais-ativo')
    visitanteMaisAtivo.value = resVisitante.data[0] || null

  } catch (error) {
    console.error('Erro ao buscar estatísticas:', error)
  }
})

</script>

<template>
  <Navbar />
   <div>
    <h1>Painel de Estatísticas</h1>

    <StatsChart :reservasData="reservasPorDia" />

    <section>
      <h2>Atração Mais Disputada</h2>
      <div v-if="atracaoMaisDisputada">
        <p>ID Atração: {{ atracaoMaisDisputada.atracao_id }}</p>
        <p>Total de reservas: {{ atracaoMaisDisputada.total }}</p>
      </div>
      <p v-else>Nenhum dado encontrado</p>
    </section>

    <section>
      <h2>Visitante Mais Ativo</h2>
      <div v-if="visitanteMaisAtivo">
        <p>ID Visitante: {{ visitanteMaisAtivo.visitante_id }}</p>
        <p>Total de reservas: {{ visitanteMaisAtivo.total }}</p>
      </div>
      <p v-else>Nenhum dado encontrado</p>
    </section>
  </div>

</template>
