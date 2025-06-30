<template>
  <Navbar />
  <main class="container p-6 space-y-2">
    <h1 class="text-xl font-bold mb-4">Sistema de Fila Virtual</h1>

    <section class="justify-center grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-8">
      <!-- 1. Entrar na Fila -->
      <div class="font-medium text-lg mb-2 shadow mx-auto" style="max-width: 500px; width: 100%;">
        <div class="card-header bg-blue-900 text-white text-center rounded-t-md fw-bold p-2">
          Entrar na Fila
        </div>
        <div class="card-body m-4">
          <form @submit.prevent="entrarNaFila">
            <div class="m-4">
              <label for="enter_visitor_email" class="form-label">Email do Visitante</label>
              <input v-model="form.visitor_email" type="text" id="enter_visitor_email"
                class="form-control w-full p-2 rounded border border-gray-300 focus:outline-none" required />
            </div>
            <div class="m-4">
              <label for="enter_attraction_name" class="form-label">Nome da Atração</label>
              <input v-model="form.attraction_name" type="text" id="enter_attraction_name"
                class="form-control w-full p-2 rounded border border-gray-300 focus:outline-none" required />
            </div>
            <div class="flex justify-center m-8">
              <PrimaryButton type="submit" class="px-4">Entrar na Fila</PrimaryButton>
            </div>
          </form>
        </div>
      </div>

      <!-- 2. Ver Fila Atual -->
      <div class="font-medium text-lg mb-2 shadow mx-auto" style="max-width: 500px; width: 100%;">
        <div class="card-header bg-blue-900 text-white text-center rounded-t-md fw-bold p-2">
          Ver Fila Atual
        </div>
        <div class="card-body m-4">
          <form @submit.prevent="verFila">
            <div class="mb-4">
              <label for="view_attraction_name" class="form-label">Nome da Atração</label>
              <input v-model="viewForm.attraction_name" type="text" id="view_attraction_name"
                class="form-control w-full p-2 rounded border border-gray-300 focus:outline-none" required />
            </div>
            <div class="flex justify-center m-8">
              <PrimaryButton type="submit" class="px-4">Ver Fila</PrimaryButton>
            </div>
          </form>

          <Modal :show="showFilaModal" @close="showFilaModal = false" maxWidth="md">
            <template #default>
              <div class="p-4">
                <h5 class="mb-3 text-center">Visitantes na Fila</h5>
                <ul class="list-group m-4 p-4" v-if="filaAtual.length">
                  <li v-for="visitante in filaAtual" :key="visitante.id"
                    class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                      {{ visitante.name }} (ID: {{ visitante.id }})
                    </span>
                    <span class="badge bg-primary rounded-pill">{{ visitante.ticket_type }}</span>
                  </li>
                </ul>
                <div v-else class="text-center text-muted">Nenhum visitante na fila.</div>
                <div class="flex justify-center m-4">
                  <PrimaryButton @click="showFilaModal = false">Fechar</PrimaryButton>
                </div>
              </div>
            </template>
          </Modal>

        </div>
      </div>

      <!-- 3. Chamar Próximo Visitante -->
      <div class="font-medium text-lg mb-2 shadow mx-auto" v-if="isAdmin" style="max-width: 500px; width: 100%;">
        <div class="card-header bg-blue-900 text-white text-center rounded-t-md fw-bold p-2">
          Chamar Próximo Visitante
        </div>
        <div class="card-body m-4">
          <form @submit.prevent="chamarProximo">
            <div class="mb-4">
              <label for="call_attraction_name" class="form-label">Nome da Atração</label>
              <input v-model="callForm.attraction_name" type="text" id="call_attraction_name"
                class="form-control w-full p-2 rounded border border-gray-300 focus:outline-none" required />
            </div>
            <div class="flex justify-center m-8">
              <PrimaryButton type="submit" class="px-4">Chamar próximo</PrimaryButton>
            </div>
          </form>
        </div>
      </div>

      <!-- 4. Ver Minha Posição na Fila -->
      <div class="font-medium text-lg mb-2 shadow mx-auto" style="max-width: 500px; width: 100%;">
        <div class="card-header bg-blue-900 text-white text-center rounded-t-md fw-bold p-2">
          Ver Minha Posição na Fila
        </div>
        <div class="card-body m-4">
          <form @submit.prevent="verPosicao">
            <div class="mb-4">
              <label for="pos_visitor_email" class="form-label">Email do Visitante</label>
              <input v-model="positionForm.visitor_email" type="text" id="pos_visitor_email"
                class="form-control w-full p-2 rounded border border-gray-300 focus:outline-none" required />
            </div>
            <div class="mb-3">
              <label for="pos_attraction_name" class="form-label">Nome da Atração</label>
              <input v-model="positionForm.attraction_name" type="text" id="pos_attraction_name"
                class="form-control w-full p-2 rounded border border-gray-300 focus:outline-none" required />
            </div>
            <div class="flex justify-center ">
              <PrimaryButton type="submit" class="px-4">Ver minha posição</PrimaryButton>
            </div>
          </form>
          <Modal :show="showPosicaoModal" @close="showPosicaoModal = false" maxWidth="md">
            <template #default>
              <div class="p-4">
                <h5 class="mb-3 text-center">Sua posição na fila</h5>
                <div class="alert alert-info text-center">
                  {{ positionMessage }}
                </div>
                <div class="flex justify-center m-8">
                  <PrimaryButton @click="showPosicaoModal = false">Fechar</PrimaryButton>
                </div>
              </div>
            </template>
          </Modal>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { usePage, Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import QueueStatsChart from '@/Components/Queue/QueueStatsChart.vue';

const showFilaModal = ref(false);
const showPosicaoModal = ref(false);
const chartKey = ref(0); // forçar recriação do gráfico


const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';

const form = ref({
  visitor_id: '',
  attraction_id: '',
  visitor_email: '',
});

const viewForm = ref({
  attraction_id: ''
});

const callForm = ref({
  attraction_id: ''
});

const positionForm = ref({
  visitor_id: '',
  attraction_id: '',
  visitor_email: '',
});

const positionMessage = ref('');
const filaAtual = ref([]); // Para armazenar a lista de visitantes da fila

async function verFila() {
  try {
    const response = await axios.get('/fila/ver', { params: viewForm.value });
    filaAtual.value = response.data.queue;
    showFilaModal.value = true; // Abre o modal
  } catch (error) {
    alert('Erro ao buscar a fila.');
    filaAtual.value = [];
  }
}
// Métodos
async function entrarNaFila() {
  try {
    const response = await axios.post('/fila/entrar', form.value);
    alert('Você entrou na fila!');
  } catch (error) {
    alert('Erro ao entrar na fila.');
  }
}

async function chamarProximo() {
  try {
    const response = await axios.post('/fila/chamar', callForm.value);
    alert(response.data.message || 'Próximo visitante chamado!');
    chartKey.value++; // forçar reload do gráfico
  } catch (error) {
    alert('Erro ao chamar próximo.');
  }
}

async function verPosicao() {
  try {
    const response = await axios.get('/fila/posicao', { params: positionForm.value });
    positionMessage.value = response.data.message;
    showPosicaoModal.value = true; // Abre o modal da posição
  } catch (error) {
    positionMessage.value = 'Erro ao verificar posição.';
    showPosicaoModal.value = true;
  }
}
</script>