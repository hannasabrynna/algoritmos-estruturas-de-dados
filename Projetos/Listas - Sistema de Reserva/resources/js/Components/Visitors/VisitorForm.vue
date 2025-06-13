
<template>
  <form @submit.prevent="submit" class="space-y-2">
    <input v-model="form.name" placeholder="Name" />
    <input v-model="form.cpf" placeholder="CPF" />
    <input v-model="form.birth_date" type="date" />
    <input v-model="form.email" type="email" />
    <select v-model="form.ticket_type">
      <option>normal</option>
      <option>VIP</option>
      <option>annual_pass</option>
    </select>
    <input v-model="form.credit_card" placeholder="Credit Card" />
    <button type="submit">Save</button>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

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
