<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Attractions List</h1>
    <table class="min-w-full bg-white shadow rounded">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2">Name</th>
          <th class="p-2">Type</th>
          <th class="p-2">Capacity</th>
          <th class="p-2">Age Limit</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="attraction in attractions" :key="attraction.id">
          <td class="p-2">{{ attraction.name }}</td>
          <td class="p-2">{{ attraction.type }}</td>
          <td class="p-2">{{ attraction.capacity_per_time_slot }}</td>
          <td class="p-2">{{ attraction.minimum_age }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  attractions: Array
});
</script>
