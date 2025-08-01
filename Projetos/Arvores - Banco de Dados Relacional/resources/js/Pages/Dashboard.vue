<script setup>
import { ref, reactive, watch } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'


// 🌱 Estado reativo
const tableName = ref('')
const columns = ref([
    { name: '', type: 'string', nullable: false }
])
const record = reactive({})

// 🔧 Função auxiliar
const getInputType = (type) => {
    switch (type) {
        case 'int':
        case 'float': return 'number'
        case 'boolean': return 'checkbox'
        case 'date': return 'date'
        default: return 'text'
    }
}

// 🎯 Atualiza os campos quando a estrutura muda
watch(columns, (newCols) => {
    newCols.forEach(col => {
        if (!(col.name in record)) {
            record[col.name] = col.type === 'boolean' ? false : ''
        }
    })
}, { deep: true })

// ➕ Adiciona nova coluna
const addColumn = () => {
    columns.value.push({ name: '', type: 'string', nullable: false })
}

const records = ref([]) // usado para exibir os registros

const submitRecord = () => {
    const payload = {
        table: tableName.value,
        schema: columns.value,
        record: { ...record },
    }

    router.post('/records', payload, {
        onSuccess: (page) => {
            records.value.push(payload.record) // adiciona à visualização
            // limpa o formulário
            columns.value.forEach(col => {
                record[col.name] = col.type === 'boolean' ? false : ''
            })
        },
        onError: (errors) => {
            alert('Erro ao inserir registro.')
            console.error(errors)
        }
    })
}


</script>

<template>

    <Head title="SylvaDB" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                SylvaDB
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <h2>🌱 Criar Tabela</h2>

                    <input v-model="tableName" placeholder="Nome da Tabela" class="mb-2" />

                    <div v-for="(col, index) in columns" :key="index" class="mb-2 flex gap-2">
                        <input v-model="col.name" placeholder="Nome da Coluna" />
                        <select v-model="col.type">
                            <option value="string">Texto</option>
                            <option value="int">Inteiro</option>
                            <option value="float">Decimal</option>
                            <option value="boolean">Booleano</option>
                            <option value="date">Data</option>
                        </select>
                        <label>
                            <input type="checkbox" v-model="col.nullable" />
                            Nullable
                        </label>
                    </div>

                    <button @click="addColumn">Adicionar Coluna</button>

                    <hr class="my-4" />

                    <h3>🌿 Inserir Registro</h3>
                    <form @submit.prevent="submitRecord">
                        <div v-for="col in columns" :key="col.name" class="mb-2">
                            <input :type="getInputType(col.type)" v-model="record[col.name]" :placeholder="col.name"
                                :required="!col.nullable" />
                        </div>
                        <button type="submit">Inserir</button>
                    </form>

                    <h3 class="mt-6">🌳 Registros Inseridos</h3>
                    <table class="table-auto w-full border mt-2">
                        <thead>
                            <tr>
                                <th v-for="col in columns" :key="col.name" class="border px-2 py-1">{{ col.name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(rec, index) in records" :key="index">
                                <td v-for="col in columns" :key="col.name" class="border px-2 py-1">
                                    {{ rec[col.name] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
