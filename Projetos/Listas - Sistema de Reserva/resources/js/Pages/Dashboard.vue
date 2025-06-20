<script setup>
import { usePage, Link } from '@inertiajs/vue3';

const page = usePage();
console.log('User:', page.props.auth.user)
</script>

<template>
  <nav class="bg-red-500 text-white p-4 flex gap-4">
    <p>TechPark</p>
    <Link href="/visitors/create">Cadastrar Visitante</Link>

    <!-- Apenas admin -->
    <Link v-if="page.props.auth?.user?.role === 'admin'" href="/atracoes">Controle de filas</Link>
    <Link v-if="page.props.auth?.user?.role === 'admin'" href="/attractions/create">Cadastrar Atração</Link>

    <Link :href="route('logout')" method="post" as="button" class="text-white hover:underline">Sair</Link>
  </nav>

  <div>
    <h1 class="text-2xl font-bold mb-4">
      Olá,
      <span v-if="page.props.auth?.user?.name">{{ page.props.auth.user.name }}</span>!
    </h1>
  </div>

  <div class="space-y-2">
    <h2>Confira as atrações do nosso Park</h2>
    <Link href="/attractions" class="text-blue-600 hover:underline">Lista de atrações</Link>
  </div>

  <!-- Só admin vê -->
  <div class="space-y-2" v-if="page.props.auth?.user?.role === 'admin'">
    <h2>Visitantes cadastrados</h2>
    <Link href="/visitors" class="text-blue-600 hover:underline">Lista de visitantes</Link>
  </div>
</template>
