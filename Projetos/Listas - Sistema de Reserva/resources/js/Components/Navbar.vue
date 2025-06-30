<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';
const isUser = page.props.auth?.user?.role === 'visitor';
</script>

<template>
    <nav class="navbar bg-blue-800 text-white px-6 py-4 flex justify-between items-center">
      <h1 class="text-xl font-bold font-impact tracking-wide">TechPark</h1>

      <div class="flex gap-4 items-center">
        <template v-if="page.props.auth?.user">

          <Link href="/attractions">Atrações</Link>
          <!-- VISITANTE logado -->
          <Link v-if="isUser" href="*">Minhas filas</Link>

          <!-- ADMIN logado -->
          <Link v-if="isAdmin" href="*">Controle de filas</Link>
          <Link v-if="isAdmin" href="/attractions/create">Cadastrar Atração</Link>
          <Link href="/visitors/create">Cadastrar Visitante</Link>

          <!-- Sair -->
          <Link :href="route('logout')" method="post" as="button" class="text-white hover:underline">Sair</Link>
        </template>

        <template v-else>
          <!-- Usuário NÃO autenticado -->
          <Link :href="route('login')">Entrar</Link>
          <Link :href="route('register')">Registrar</Link>
        </template>
      </div>
  </nav>
</template>

