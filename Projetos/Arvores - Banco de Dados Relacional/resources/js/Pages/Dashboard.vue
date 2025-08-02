<script setup>
import { ref, reactive, watch } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const currentTable = ref(null)
const tableName = ref('')
const allTables = ref([])

const schemas = reactive({})
const records = reactive({})
const newRecord = reactive({})
const newColumns = ref([{ name: '', type: 'string', nullable: false, foreignTable: '' }])

const editingIndex = ref(null)
const editingRecord = reactive({})

watch(newColumns, (newCols) => {
    if (!currentTable.value) return
    newCols.forEach(col => {
        if (!(col.name in newRecord)) {
            newRecord[col.name] = col.type === 'boolean' ? false : ''
        }
    })
}, { deep: true })

const getInputType = (type) => {
    switch (type) {
        case 'int':
        case 'float': return 'number'
        case 'boolean': return 'checkbox'
        case 'date': return 'date'
        default: return 'text'
    }
}

const addColumn = () => {
    newColumns.value.push({ name: '', type: 'string', nullable: false, foreignTable: '' })
}

const createTable = () => {
    const name = tableName.value.trim()
    if (!name || allTables.value.includes(name)) return alert('Nome inválido ou já existe.')

    allTables.value.push(name)
    schemas[name] = [...newColumns.value]
    records[name] = []
    currentTable.value = name

    tableName.value = ''
    newColumns.value = [{ name: '', type: 'string', nullable: false, foreignTable: '' }]
    Object.keys(newRecord).forEach(key => delete newRecord[key])
}

const submitRecord = () => {
    if (!currentTable.value) return

    const payload = {
        table: currentTable.value,
        schema: schemas[currentTable.value],
        record: { ...newRecord }
    }

    router.post('/records', payload, {
        onSuccess: () => {
            records[currentTable.value].push(payload.record)
            Object.keys(newRecord).forEach(key => {
                newRecord[key] = typeof newRecord[key] === 'boolean' ? false : ''
            })
        },
        onError: (err) => {
            alert('Erro ao inserir registro.')
            console.error(err)
        }
    })
}

const removeRecord = (index) => {
    if (!confirm('Deseja realmente remover este registro?')) return
    records[currentTable.value].splice(index, 1)
}

const startEdit = (index) => {
    editingIndex.value = index
    Object.assign(editingRecord, records[currentTable.value][index])
}

const saveEdit = () => {
    if (editingIndex.value === null) return
    records[currentTable.value][editingIndex.value] = { ...editingRecord }
    editingIndex.value = null
    Object.keys(editingRecord).forEach(k => delete editingRecord[k])
}

const cancelEdit = () => {
    editingIndex.value = null
    Object.keys(editingRecord).forEach(k => delete editingRecord[k])
}
</script>

<template>
    <Head title="SylvaDB" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">SylvaDB</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <section>
                    <h2 class="text-lg font-bold mb-2">Criar Nova Tabela</h2>
                    <input v-model="tableName" placeholder="Nome da Tabela" class="mb-2 border p-1" />
                    <div v-for="(col, index) in newColumns" :key="index" class="mb-2 flex gap-2">
                        <input v-model="col.name" placeholder="Nome da Coluna" class="border p-1" />
                        <select v-model="col.type" class="border p-1">
                            <option value="string">Texto</option>
                            <option value="int">Inteiro</option>
                            <option value="float">Decimal</option>
                            <option value="boolean">Booleano</option>
                            <option value="date">Data</option>
                            <option value="foreign">Chave Estrangeira</option>
                        </select>
                        <select v-if="col.type === 'foreign'" v-model="col.foreignTable" class="border p-1">
                            <option v-for="t in allTables" :key="t" :value="t">{{ t }}</option>
                        </select>
                        <label>
                            <input type="checkbox" v-model="col.nullable" /> Nullable
                        </label>
                    </div>
                    <button @click="addColumn" class="bg-blue-500 text-white px-3 py-1 rounded">Adicionar Coluna</button>
                    <button @click="createTable" class="ml-2 bg-green-600 text-white px-3 py-1 rounded">Criar Tabela</button>
                </section>

                <hr class="my-6" />

                <section v-if="allTables.length">
                    <h2 class="text-lg font-bold mb-2">Tabelas Existentes</h2>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button
                            v-for="table in allTables"
                            :key="table"
                            @click="currentTable = table"
                            class="px-3 py-1 rounded border"
                            :class="{ 'bg-gray-800 text-white': currentTable === table }"
                        >
                            {{ table }}
                        </button>
                    </div>
                </section>

                <section v-if="currentTable">
                    <h3 class="text-lg font-bold">Inserir Registro em "{{ currentTable }}"</h3>
                    <form @submit.prevent="submitRecord" class="mb-4 mt-2">
                        <div v-for="col in schemas[currentTable]" :key="col.name" class="mb-2">
                            <input
                                v-if="col.type !== 'foreign'"
                                :type="getInputType(col.type)"
                                v-model="newRecord[col.name]"
                                :placeholder="col.name"
                                :required="!col.nullable"
                                class="border p-1 w-full"
                            />
                            <select
                                v-else
                                v-model="newRecord[col.name]"
                                :required="!col.nullable"
                                class="border p-1 w-full"
                            >
                                <option disabled value="">Selecione {{ col.name }}</option>
                                <option
                                    v-for="rec in records[col.foreignTable] || []"
                                    :key="rec.id"
                                    :value="rec.id"
                                >
                                    {{ Object.values(rec).join('') }}
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Inserir</button>
                    </form>

                    <h4 class="font-semibold mb-1">Registros em "{{ currentTable }}"</h4>
                    <table class="table-auto w-full border mt-2">
                        <thead>
                            <tr>
                                <th v-for="col in schemas[currentTable]" :key="col.name" class="border px-2 py-1">
                                    {{ col.name }}
                                </th>
                                <th class="border px-2 py-1">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(rec, index) in records[currentTable]" :key="index">
                                <td v-for="col in schemas[currentTable]" :key="col.name" class="border px-2 py-1">
                                    <template v-if="editingIndex === index">
                                        <input v-model="editingRecord[col.name]" :type="getInputType(col.type)" class="border p-1 w-full" />
                                    </template>
                                    <template v-else>
                                        {{ rec[col.name] }}
                                    </template>
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    <template v-if="editingIndex === index">
                                        <button @click="saveEdit" class="text-green-600">Salvar</button>
                                        <button @click="cancelEdit" class="text-gray-500 ml-2">Cancelar</button>
                                    </template>
                                    <template v-else>
                                        <button @click="startEdit(index)" class="text-blue-600">Editar</button>
                                        <button @click="removeRecord(index)" class="text-red-600 ml-2">Remover</button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
