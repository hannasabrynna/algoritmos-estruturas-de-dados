<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';
const isUser = page.props.auth?.user?.role === 'visitor';
</script>

<template>
  <nav class="navbar bg-blue-800 text-white px-8 py-4 flex justify-between items-center">
    <h1 v-if="page.props.auth?.user" class="text-xl font-bold font-impact tracking-wide">
      <Link href="/dashboard">TechPark</Link>
    </h1>
    <h1 v-else class="text-xl font-bold font-impact tracking-wide">
      <Link href="/">TechPark</Link>
    </h1>

    <div class="absolute left-1/2 transform -translate-x-1/2 flex gap-4 items-center">
      <template v-if="page.props.auth?.user">
        <Link href="/attractions">Atrações</Link>
        <!-- VISITANTE logado -->
        <Link v-if="isUser" href="/fila">Filas</Link>

        <!-- ADMIN logado -->
        <Link v-if="isAdmin" href="/fila">Controle de filas</Link>
        <Link v-if="isAdmin" href="/stats">Estatísticas</Link>
        <Link v-if="isAdmin" href="/attractions/create">Cadastrar Atração</Link>
        <Link v-if="isAdmin" href="/visitors">Visitantes</Link>
        <Link href="/visitors/create">Cadastrar Visitante</Link>
      </template>
    </div>

    <div class="flex gap-4 items-center">
      <template v-if="page.props.auth?.user">
        <!-- Sair -->
        <Link :href="route('logout')" method="post" as="button" class="text-white hover:underline">Sair</Link>
      </template>

      <template v-else>
        <!-- Usuário NÃO autenticado -->
        <Link :href="route('login')">Entrar</Link>
        <Link :href="route('register')">Cadastre-se</Link>
      </template>
    </div>
  </nav>
</template>
