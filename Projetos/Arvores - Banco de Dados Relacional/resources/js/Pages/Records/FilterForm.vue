<template>
  <div class="p-6 max-w-xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Filtrar Tabela: {{ tableName }}</h2>

    <form @submit.prevent="submitFilter" class="space-y-4">
      <div>
        <label class="block text-sm font-semibold">Campo</label>
        <select v-model="form.field" class="w-full border rounded p-2">
          <option disabled value="">Selecione um campo</option>
          <option v-for="field in fields" :key="field.name" :value="field.name">
            {{ field.name }} ({{ field.type }})
          </option>

        </select>
      </div>

      <div>
        <label class="block text-sm font-semibold">Operador</label>
        <select v-model="form.operator" class="w-full border rounded p-2">
          <option value="=">=</option>
          <option value="!=">!=</option>
          <option value=">">></option>
          <option value="<"><</option>
          <option value=">=">>=</option>
          <option value="<="><=</option>
          <option value="contains">Contém</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-semibold">Valor</label>
        <input v-model="form.value" type="text" class="w-full border rounded p-2" />
      </div>

      <input type="hidden" v-model="form.table" />

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        Filtrar
      </button>
    </form>

    <div class="mt-6">
      <a :href="`/dashboard`" class="text-blue-600 hover:underline">Voltar a tabela</a>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  tableName: String,
  fields: Array,
});

const form = ref({
  table: props.tableName,
  field: '',
  operator: '=',
  value: '',
});

// const submitFilter = () => {
//   router.post('/tabelas/filter', form.value);
// };
const submitFilter = () => {
  router.visit('/tabelas/filter', {
    method: 'post',
    data: form.value,
  });
};
</script>
