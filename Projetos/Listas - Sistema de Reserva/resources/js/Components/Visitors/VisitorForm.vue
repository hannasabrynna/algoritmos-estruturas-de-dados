<script setup>
import { useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue'; 

const form = useForm({
  name: '',
  cpf: '',
  birth_date: '',
  email: '',
  ticket_type: 'normal',
  credit_card: '',
});

function submit() {
  form.post('/visitors');
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
        placeholder="Digite o seu nome"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- CPF -->
    <div>
      <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
      <input
        id="cpf"
        v-model="form.cpf"
        type="text"
        placeholder="Digite o seu CPF"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- Data de Nascimento -->
    <div>
      <label for="birth_date" class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
      <input
        id="birth_date"
        v-model="form.birth_date"
        type="date"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
      <input
        id="email"
        v-model="form.email"
        type="email"
        placeholder="Digite seu e-mail"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- Tipo de Ingresso -->
    <div>
      <label for="ticket_type" class="block text-sm font-medium text-gray-700">Tipo de Ingresso</label>
      <select
        id="ticket_type"
        v-model="form.ticket_type"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      >
        <option value="" disabled selected>Selecione o tipo de ingresso</option>
        <option value="normal">Normal</option>
        <option value="VIP">VIP</option>
        <option value="annual_pass">Annual Pass</option>
      </select>
    </div>

    <!-- Cartão de Crédito -->
    <div>
      <label for="credit_card" class="block text-sm font-medium text-gray-700">Número do Cartão de Crédito</label>
      <input
        id="credit_card"
        v-model="form.credit_card"
        type="text"
        placeholder="Digite o número do seu cartão de crédito"
        class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        required
      />
    </div>

    <!-- Botão de Submissão -->
    <div>
      <PrimaryButton
        type="submit"
        class="w-full py-2 mt-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
      >
        Salvar
      </PrimaryButton>
    </div>
  </form>
</template>


