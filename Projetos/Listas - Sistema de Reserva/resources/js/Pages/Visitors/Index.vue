<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Visitors List</h1>
    <table class="min-w-full bg-white shadow rounded">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2">Name</th>
          <th class="p-2">Email</th>
          <th class="p-2">CPF</th>
          <th class="p-2">Ticket Type</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="visitor in visitors" :key="visitor.id">
          <td class="p-2">{{ visitor.name }}</td>
          <td class="p-2">{{ visitor.email }}</td>
          <td class="p-2">{{ visitor.cpf }}</td>
          <td class="p-2">{{ visitor.ticket_type }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  visitors: Array
});
</script>
