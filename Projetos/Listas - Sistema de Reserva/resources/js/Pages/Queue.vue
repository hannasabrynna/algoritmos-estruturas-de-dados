<template>
  <Navbar />
  <div class="container py-5">
    <h1 class="mb-5 text-center">Sistema de Fila Virtual</h1>

    <!-- 1. Entrar na Fila -->
    <div class="card mb-4 shadow mx-auto" style="max-width: 500px; width: 100%;">
      <div class="card-header bg-primary text-black text-center fw-bold">
        1. Entrar na Fila
      </div>
      <div class="card-body">
        <form @submit.prevent="entrarNaFila">
          <div class="mb-3">
            <label for="enter_visitor_id" class="form-label">ID do Visitante</label>
            <input
              v-model="form.visitor_id"
              type="number"
              id="enter_visitor_id"
              class="form-control"
              required
            />
          </div>
          <div class="mb-3">
            <label for="enter_attraction_id" class="form-label">ID da Atração</label>
            <input
              v-model="form.attraction_id"
              type="number"
              id="enter_attraction_id"
              class="form-control"
              required
            />
          </div>
          <div class="d-flex justify-content-center ">
           <PrimaryButton type="submit" class="px-4">Entrar na Fila</PrimaryButton>
          </div>
        </form>
      </div>
    </div>

    <!-- 2. Ver Fila Atual -->
    <div class="card mb-4 shadow mx-auto" style="max-width: 500px; width: 100%;">
      <div class="card-header bg-secondary text-black text-center fw-bold">
        2. Ver Fila Atual
      </div>
      <div class="card-body">
        <form @submit.prevent="verFila">
          <div class="mb-3">
            <label for="view_attraction_id" class="form-label">ID da Atração</label>
            <input
              v-model="viewForm.attraction_id"
              type="number"
              id="view_attraction_id"
              class="form-control"
              required
            />
          </div>
         <div class="d-flex justify-content-center ">
           <PrimaryButton type="submit" class="px-4">Ver Fila</PrimaryButton>
          </div>
        </form>

        <Modal :show="showFilaModal" @close="showFilaModal = false" maxWidth="md">
        <template #default>
            <h5 class="mb-3 text-center">Visitantes na Fila</h5>
            <ul class="list-group mb-2" v-if="filaAtual.length">
            <li
                v-for="visitante in filaAtual"
                :key="visitante.id"
                class="list-group-item d-flex justify-content-between align-items-center"
            >
                <span>
                {{ visitante.name }} (ID: {{ visitante.id }})
                </span>
                <span class="badge bg-primary rounded-pill">{{ visitante.ticket_type }}</span>
            </li>
            </ul>
            <div v-else class="text-center text-muted">Nenhum visitante na fila.</div>
            <div class="d-flex justify-content-center mt-3">
            <PrimaryButton @click="showFilaModal = false">Fechar</PrimaryButton>
            </div>
        </template>
        </Modal>
 
      </div>
    </div>

    <!-- 3. Chamar Próximo Visitante -->
    <div class="card mb-4 shadow mx-auto"  v-if="isAdmin" style="max-width: 500px; width: 100%;">
      <div class="card-header bg-danger text-black text-center fw-bold">
        3. Chamar Próximo Visitante
      </div>
      <div class="card-body">
        <form @submit.prevent="chamarProximo">
          <div class="mb-3">
            <label for="call_attraction_id" class="form-label">ID da Atração</label>
            <input
              v-model="callForm.attraction_id"
              type="number"
              id="call_attraction_id"
              class="form-control"
              required
            />
          </div>
         <div class="d-flex justify-content-center ">
           <PrimaryButton type="submit" class="px-4">Chamar proxima na Fila</PrimaryButton>
          </div>
        </form>
      </div>
    </div>

    <!-- 4. Ver Minha Posição na Fila -->
    <div class="card mb-4 shadow mx-auto" style="max-width: 500px; width: 100%;">
      <div class="card-header bg-info text-black text-center fw-bold">
        4. Ver Minha Posição na Fila
      </div>
      <div class="card-body">
        <form @submit.prevent="verPosicao">
          <div class="mb-3">
            <label for="pos_visitor_id" class="form-label">ID do Visitante</label>
            <input
              v-model="positionForm.visitor_id"
              type="number"
              id="pos_visitor_id"
              class="form-control"
              required
            />
          </div>
          <div class="mb-3">
            <label for="pos_attraction_id" class="form-label">ID da Atração</label>
            <input
              v-model="positionForm.attraction_id"
              type="number"
              id="pos_attraction_id"
              class="form-control"
              required
            />
          </div>
        <div class="d-flex justify-content-center ">
           <PrimaryButton type="submit" class="px-4">Ver minha posição Fila</PrimaryButton>
          </div>
        </form>
        <Modal :show="showPosicaoModal" @close="showPosicaoModal = false" maxWidth="md">
        <template #default>
            <h5 class="mb-3 text-center">Sua posição na fila</h5>
            <div class="alert alert-info text-center">
            {{ positionMessage }}
            </div>
            <div class="d-flex justify-content-center mt-3">
            <PrimaryButton @click="showPosicaoModal = false">Fechar</PrimaryButton>
            </div>
        </template>
        </Modal>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { usePage, Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';

const showFilaModal = ref(false);
const showPosicaoModal = ref(false);
const page = usePage();
const isAdmin = page.props.auth?.user?.role === 'admin';

const form = ref({
  visitor_id: '',
  attraction_id: ''
});

const viewForm = ref({
  attraction_id: ''
});

const callForm = ref({
  attraction_id: ''
});

const positionForm = ref({
  visitor_id: '',
  attraction_id: ''
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

<style scoped>
.card {
  border-radius: 12px;
}
.card-header {
  font-size: 1.15rem;
  letter-spacing: 0.5px;
}
.btn {
  min-width: 150px;
}
</style>