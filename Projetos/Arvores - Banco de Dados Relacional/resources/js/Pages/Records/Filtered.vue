<template>
   <FilterLayout>

  <!-- <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8"> -->
      <section class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-bold mb-8">Resultado - Tabela: {{ table }}</h2>
      <!-- <h2 class="text-2xl font-bold mb-4">Tabela: {{ table }}</h2> -->

    <div v-if="records.length > 0">
      <table class="w-full border text-sm text-left">
        <thead>
          <tr class="bg-gray-100">
            <th v-for="(value, key) in records[0]" :key="key" class="p-2 border">{{ key }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(record, index) in records" :key="index">
            <td v-for="(value, key) in record" :key="key" class="p-2 border">
              {{ value }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="text-gray-500 mt-6">
      Nenhum resultado encontrado com os critérios fornecidos.
    </div>
      </section>

      <div class="mt-8">   
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mr-3 transition">
           <a :href="`/tabelas/${table}/filtro`" class="">Voltar ao filtro</a>
        </button>
    </div>
    <!-- </div>
  </div> -->
   </FilterLayout>
</template>

<script setup>
import FilterLayout from '@/Layouts/FilterLayout.vue'

defineProps({
  table: String,
  records: Array,
});
</script>
