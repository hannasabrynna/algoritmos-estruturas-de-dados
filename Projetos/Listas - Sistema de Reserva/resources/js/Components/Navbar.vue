<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';
const isUser = page.props.auth?.user?.role === 'visitor';
</script>

<template>
    <nav class="navbar">
    <h1>TechPark</h1>
    <Link href="/visitors/create">Cadastrar Visitante</Link>
    <Link v-if="isUser" href="*">Minhas filas</Link>
    

    <!-- Apenas admin -->
    <Link v-if="isAdmin" href="/fila">Controle de filas</Link>
    <Link v-if="isAdmin" href="/attractions/create">Cadastrar Atração</Link>

    <Link :href="route('logout')" method="post" as="button" class="text-white hover:underline">Sair</Link>
  </nav>
</template>


<style scoped>
/* Aqui você pode adicionar algum estilo específico para o componente */
.navbar {
  background-color: #6b9fd3;
  color: white;
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

h1{
  color: white;
  font-size: 1.5rem;
  font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
  margin: 0;
}
</style>
