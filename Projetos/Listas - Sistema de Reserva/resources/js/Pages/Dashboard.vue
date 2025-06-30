<script setup>
import Navbar from '@/Components/Navbar.vue';
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
defineProps({
  attractions: Array
});
const isAdmin = page.props.auth?.user?.role === 'admin';
</script>

<template>
  <Navbar />

  <div class="m-6">
    <h1 class="text-xl font-bold mb-4">
      Olá,
      <span v-if="page.props.auth?.user?.name">{{ page.props.auth.user.name }}</span>! <br>
    </h1>
  </div>



  <main class="p-6 space-y-2">
    <section>
      <div class="space-y-2">
        <h2 class="text-xl font-bold mb-4">Confira nossas atrações</h2>
  
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <div v-for="attraction in attractions" :key="attraction.id"
            class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow p-4 flex flex-col justify-between">
            <h3 class="text-2xl font-bold mb-2 text-center text-blue-950">{{ attraction.name }}</h3>
            <p class="mb-4">
              <strong>Tipo:</strong> {{ attraction.type }}
            </p>
  
            <div class="flex justify-between items-center text-white bg-blue-900 rounded-lg p-3">
              <div class="flex items-center space-x-2">
  
                <span>Capacidade</span>
              </div>
              <span class="font-bold text-md">{{ attraction.capacity_per_time_slot }} pessoas</span>
            </div>
  
            <div class="flex justify-between items-center text-white bg-blue-900 rounded-lg p-3 mt-3">
              <div class="flex items-center space-x-2">
                <span>Idade Mínima</span>
              </div>
              <span class="font-bold text-md">{{ attraction.minimum_age }} anos</span>
            </div>
  
            <div class="flex justify-between items-center text-white bg-blue-900 rounded-lg p-3 mt-3">
              <div class="flex items-center space-x-2">
                <span>Horários</span>
              </div>
              <span class="font-bold text-md">
                <span v-if="Array.isArray(attraction.available_time_slots)">
                  {{ attraction.available_time_slots.join(', ') }}
                </span>
                <span v-else>
                  {{ attraction.available_time_slots }}
                </span>
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="space-y-2">
      <h2>Fila</h2>
      <Link href="/fila" class="text-blue-600 hover:underline">Fila</Link>
    </div>

    <!-- Só admin vê -->
    <div class="space-y-2" v-if="isAdmin">
      <h2>Lista de Visitantes cadastrados</h2>
      <Link href="/visitors" class="text-blue-600 hover:underline">Lista de visitantes</Link>
    </div>

    <div class="space-y-2" v-if="isAdmin">
      <h2>Estatísticas e Métricas</h2>
      <Link href="/stats" class="text-blue-600 hover:underline">Lista de visitantes</Link>
    </div>
  </main>

</template>
