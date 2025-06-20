<template>
  <nav class="bg-red-500 text-white p-4 flex gap-4">
    <p>TechPark</p>
    <router-link to="/visitors/create">Cadastrar Visitante</router-link>

    <!-- Apenas admin -->
    <router-link v-if="isAdmin" to="/atracoes">Controle de filas</router-link>
    <router-link v-if="isAdmin" to="/attractions/create">Cadastrar Atração</router-link>

    <router-link :to="{ name: 'logout' }" class="text-white hover:underline">Sair</router-link>
  </nav>
</template>

<script>
export default {
  computed: {
    // Verificando o papel do usuário (admin)
    isAdmin() {
      return this.$page.props.auth?.user?.role === 'admin';
    }
  }
}
</script>

<style scoped>
/* Aqui você pode adicionar algum estilo específico para o componente */
</style>
