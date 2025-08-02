<template>
  <div class="p-6 max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Resultados do Filtro - Tabela: {{ table }}</h2>

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

    <div class="mt-6">
      <a :href="`/tabelas/${table}/filtro`" class="text-blue-600 hover:underline">Voltar ao filtro</a>
    </div>
  </div>
</template>

<script setup>
defineProps({
  table: String,
  records: Array,
});
</script>
