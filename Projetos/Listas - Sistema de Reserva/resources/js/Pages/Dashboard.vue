<script setup>
import Navbar from '@/Components/Navbar.vue';
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';
console.log('User:', page.props.auth.user)
</script>

<template>
<Navbar />

  <div class="m-6">
    <h1 class="text-xl font-bold mb-4">
      Olá,
      <span v-if="page.props.auth?.user?.name">{{ page.props.auth.user.name }}</span>! <br>
    </h1>
  </div>



  <main class="p-6 space-y-2">
    <div class="space-y-2">
      <h2>Confira as atrações do nosso Park</h2>
      <Link href="/attractions" class="text-blue-600 hover:underline">Lista de atrações</Link>
    </div>

    <div class="space-y-2">
      <h2>Fila (só pra teste)</h2>
      <Link href="/fila" class="text-blue-600 hover:underline">Fila</Link>
    </div>

  <!-- Só admin vê -->
    <div class="space-y-2" v-if="isAdmin">
      <h2>Lista de Visitantes cadastrados</h2>
      <Link href="/visitors" class="text-blue-600 hover:underline">Lista de visitantes</Link>
    </div>
  </main>

</template>
