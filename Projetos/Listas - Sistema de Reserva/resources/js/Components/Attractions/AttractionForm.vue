<template>
  <form @submit.prevent="submit" class="space-y-2">
    <input v-model="form.name" placeholder="Name" />
    <input v-model="form.type" placeholder="Type" />
    <input v-model.number="form.capacity_per_time_slot" placeholder="Capacity" />
    <input v-model="form.available_time_slots" placeholder="Time slots (e.g., 10:00,11:00)" />
    <input v-model.number="form.minimum_age" placeholder="Minimum Age" />
    <label>
      <input type="checkbox" v-model="form.has_priority_access" />
      Priority Access
    </label>
    <button type="submit">Save</button>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

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
