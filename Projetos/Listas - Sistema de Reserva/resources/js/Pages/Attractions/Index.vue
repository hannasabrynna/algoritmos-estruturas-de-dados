<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import BackButton from '@/Components/BackButton.vue';
import Navbar from '@/Components/Navbar.vue';

const props = defineProps({
  attractions: Array,
});

const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';
const isUser = page.props.auth?.user?.role === 'visitor';
</script>

<template>
  <Navbar />

  <main class="p-6">
    <section>
      <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Atrações</h1>

        <!-- Tabela para admin -->
        <table v-if="isAdmin" class="min-w-full bg-white shadow rounded">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2">Nome</th>
              <th class="p-2">Tipo</th>
              <th class="p-2">Capacidade</th>
              <th class="p-2">Idade mínima</th>
              <th class="p-2">Horários</th>
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

        <!-- Cards para visitante -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
          <div v-for="attraction in attractions" :key="attraction.id"
            class="bg-white border rounded-xl shadow hover:shadow-lg transition-shadow p-8 flex flex-col justify-between">
            <h3 class="text-2xl font-bold mb-2 text-center text-blue-950">{{ attraction.name }}</h3>
            <p class="mb-4">
              <strong>Tipo:</strong> {{ attraction.type }}
            </p>

            <div class="flex justify-between items-center text-blue-900 border border-blue-900 rounded-lg p-3">
              <div class="flex items-center space-x-2">
                <span>Capacidade</span>
              </div>
              <span class="font-bold text-md">{{ attraction.capacity_per_time_slot }} pessoas</span>
            </div>

            <div class="flex justify-between items-center text-blue-900 border border-blue-900  rounded-lg p-3 mt-3">
              <div class="flex items-center space-x-2">
                <span>Idade Mínima</span>
              </div>
              <span class="font-bold text-md">{{ attraction.minimum_age }} anos</span>
            </div>

            <div class="flex justify-between items-center text-blue-900 border border-blue-900  rounded-lg p-3 mt-3">
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
  </main>
</template>
