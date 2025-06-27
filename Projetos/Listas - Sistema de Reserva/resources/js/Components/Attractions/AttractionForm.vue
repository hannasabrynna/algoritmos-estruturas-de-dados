<script setup>
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue'; 


const form = useForm({
  name: '',
  type: '',
  capacity_per_time_slot: '',
  available_time_slots: '',
  minimum_age: '',
  has_priority_access: false,
});

function submit() {
  const payload = {
    ...form.data(),
    available_time_slots: form.available_time_slots.split(','),
  };
  form.transform(() => payload).post('/attractions');
}
</script>


<template>
  <form @submit.prevent="submit" class="space-y-6 p-6 bg-white shadow-2xl rounded-lg max-w-md mx-auto">
    <!-- Nome -->
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
      <input
        id="name"
        v-model="form.name"
        type="text"
        placeholder="Digite o nome da atração"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- Tipo -->
    <div>
      <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Atração</label>
      <select
        id="type"
        v-model="form.type"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      >
        <option>Montanha-russa</option>
        <option>Roda-gigante</option>
        <option>Carrossel</option>
        <option>Brinquedo Infantil</option>
        <option>Simulador</option>
      </select>
    </div>

    <!-- Capacidade -->
    <div>
      <label for="capacity_per_time_slot" class="block text-sm font-medium text-gray-700">Capacidade por vez</label>
      <input
        id="capacity_per_time_slot"
        v-model.number="form.capacity_per_time_slot"
        type="number"
        placeholder="Digite a capacidade"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- Capacidade por hora -->
    <div>
      <label for="available_time_slots" class="block text-sm font-medium text-gray-700">Horarios disponiveis</label>
      <input
        id="available_time_slots"
        v-model="form.available_time_slots"
        type="text"
        placeholder="Hoararios disponíveis (ex: 10:00, 11:00)"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- Idade mínima -->
    <div>
      <label for="minimum_age" class="block text-sm font-medium text-gray-700">Idade mínima</label>
      <select
        id="minimum_age"
        v-model.number="form.minimum_age"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      >
        <option>1 ano</option>
        <option>4 anos</option>
        <option>7 anos</option>
        <option>12 anos</option>
        <option>15 anos</option>
        <option>18 anos</option>
      </select>
    </div>


    <!-- Priority Access -->
    <div class="flex items-center space-x-2">
      <input
        id="has_priority_access"
        type="checkbox"
        v-model="form.has_priority_access"
        class="h-5 w-5 text-blue-500 focus:ring-blue-500"
      />
      <label for="has_priority_access" class="text-sm text-gray-700">Priority Access</label>
    </div>

    <!-- Botão de Submissão -->
    <div>
      <PrimaryButton type="submit" class="w-full py-2 mt-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
        Salvar
      </PrimaryButton>
    </div>
  </form>
</template>



