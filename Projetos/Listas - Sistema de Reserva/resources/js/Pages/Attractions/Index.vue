

<template>
  <Navbar />  
  
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Atrações</h1>
    <table class="min-w-full bg-white shadow rounded">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2">Nome</th>
          <th class="p-2">Tipo</th>
          <th class="p-2">Capacidade</th>
          <th class="p-2">Idade minima</th>
          <th class="p-2">Horarios</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="attraction in attractions" :key="attraction.id">
          <td class="p-2">{{ attraction.name }}</td>
          <td class="p-2">{{ attraction.type }}</td>
          <td class="p-2">{{ attraction.capacity_per_time_slot }}</td>
          <td class="p-2">{{ attraction.minimum_age }}</td>
          <td class="p-2">{{ attraction.available_time_slots }}</td>
        </tr>
      </tbody>
    </table>

    <div class="mt-4">
      <BackButton />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import BackButton from '@/Components/BackButton.vue';
import Navbar from '@/Components/Navbar.vue';
defineProps({
  attractions: Array
});
</script>
