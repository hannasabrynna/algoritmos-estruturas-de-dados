<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <p>Bem vindo ao TechPark!</p>
    <ul class="space-y-2">
      <li>
        <Link class="text-blue-500" href="/visitors/create">Registrar Visitante</Link>
      </li>
      <li>
        <Link class="text-blue-500" href="/attractions/create">Registrar Atrações</Link>
      </li>
    </ul>
  </div>
   <div class="space-y-2">
    <h2>Listar</h2>
      <Link href="/visitors" class="text-blue-600 hover:underline">Lista de vistantentes </Link>
      <Link href="/attractions" class="text-blue-600 hover:underline">Lista de atrações </Link>
    </div>
</template>


