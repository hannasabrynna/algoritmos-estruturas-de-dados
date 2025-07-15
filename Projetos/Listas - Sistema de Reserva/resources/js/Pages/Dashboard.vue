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

  <Head title="TechPark" />
  <Navbar />

  <section class="w-full relative">
    <img src="/images/banner.jpg" alt="Banner do TechPark" class="w-full h-72 sm:h-96 md:h-[300px] object-cover" />
    <div class="absolute inset-0 flex items-end justify-start p-8">
      <div class="bg-orange-600 text-center px-6 py-4 rounded-lg shadow-lg">
        <h1 class="text-xl sm:text-xl md:text-2xl font-bold text-white">
          Diversão garantida para todas as idades!
        </h1>
        <p class="text-sm text-white mt-2">Venha viver momentos inesquecíveis no TechPark</p>
      </div>
    </div>
  </section>

  

  <main class="p-8 space-y-2">
    <section>
      <div class="space-y-2">
        <h2 class="text-xl font-bold mb-4">Confira nossas atrações</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
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
