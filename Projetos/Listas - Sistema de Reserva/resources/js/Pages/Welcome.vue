<script setup>
import Navbar from '@/Components/Navbar.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    attractions: {
        type: Array,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>

    <Head title="TechPark" />
    <Navbar />

    <section class="w-full relative">
        <img src="/images/banner.jpg" alt="Banner do TechPark" class="w-full h-72 sm:h-96 md:h-[300px] object-cover" />
        <div class="absolute inset-0 flex items-end justify-start p-8">
            <div class="bg-orange-600 text-center px-6 py-4 rounded-lg shadow-lg">
                <h1 class="text-xl sm:text-xl md:text-2xl font-bold text-white">
                    Diversão garantida para todas as idades!
                </h1>
                <p class="text-sm text-white mt-2">Venha viver momentos inesquecíveis no TechPark</p>
            </div>
        </div>
    </section>

    <main class="py-8 space-y-2">
        <section class="my-16">
            <h2 class="text-xl font-bold mb-6 text-center">Tipos de Ingresso</h2>

            <div class="flex flex-col items-center space-y-8 md:space-y-0 md:flex-row md:space-x-6 justify-center">
                 <!-- Ingresso Normal -->
                <div class="flex-row items-center justify-center mx-4 mt-4 px-6 py-2 bg-white shadow-lg rounded-lg">
                    <div class="flex items-center justify-center w-full h-40 bg-blue-900 text-center rounded-lg"><span class="font-bold text-white text-lg">Ingresso VIP</span></div>
                    <div class="p-4 text-center">
                        <p class="text-gray-600 text-sm mt-2 text-justify">Acesso a todas as atrações durante o horário regular do
                            parque.
                        </p>
                        <button
                            class="mt-4 px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-900 transition">Ingresso VIP</button>
                    </div>
                </div>

                <!-- Ingresso VIP -->
                <div class="flex-row items-center justify-center mx-4 mt-4 px-6 py-2 bg-white shadow-lg rounded-lg">
                    <div class="flex items-center justify-center w-full h-40 bg-orange-600 text-center rounded-lg"><span class="font-bold text-white text-lg">Ingresso VIP</span></div>
                    <div class="p-4 text-center">
                        <p class="text-gray-600 text-sm mt-2 text-justify">Acesso prioritário e áreas exclusivas para maior conforto.
                        </p>
                        <button
                            class="mt-4 px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-600 transition">Ingresso VIP</button>
                    </div>
                </div>

                <!-- Passe Anual -->
                <div class="flex-row items-center justify-center mx-4 mt-4 px-6 py-2 bg-white shadow-lg rounded-lg">
                    <div class="flex items-center justify-center w-full h-40 bg-green-600 text-center rounded-lg"><span class="font-bold text-white text-lg">Passe Anual</span></div>
                    <div class="p-4 text-center">
                        <p class="text-gray-600 text-sm mt-2 text-justify">Visitas ilimitadas durante o ano inteiro e descontos
                            especiais.</p>
                        <button
                            class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-600 transition">Passe Anual</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="w-full h-full my-16 p-8 bg-gray-50">
            <div class= "max-w-7xl mx-auto py-8 space-y-8">
                <h2 class="text-xl font-bold mb-4">Confira nossas atrações</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div v-for="attraction in attractions" :key="attraction.id"
                        class="bg-white border rounded-xl shadow hover:shadow-lg transition-shadow p-8 flex flex-col justify-between">
                        <h3 class="text-2xl font-bold mb-2 text-center text-blue-950">{{ attraction.name }}</h3>
                        <p class="mb-4">
                            <strong>Tipo:</strong> {{ attraction.type }}
                        </p>

                        <div
                            class="flex justify-between items-center text-blue-900 border border-blue-900 rounded-lg p-3">
                            <div class="flex items-center space-x-2">
                                <span>Capacidade</span>
                            </div>
                            <span class="font-bold text-md">{{ attraction.capacity_per_time_slot }} pessoas</span>
                        </div>

                        <div
                            class="flex justify-between items-center text-blue-900 border border-blue-900  rounded-lg p-3 mt-3">
                            <div class="flex items-center space-x-2">
                                <span>Idade Mínima</span>
                            </div>
                            <span class="font-bold text-md">{{ attraction.minimum_age }} anos</span>
                        </div>

                        <div
                            class="flex justify-between items-center text-blue-900 border border-blue-900  rounded-lg p-3 mt-3">
                            <div class="flex items-center space-x-2">
                                <span>Horários</span>
                            </div>
                            <span class="font-bold text-md">
                                <span v-if="Array.isArray(attraction.available_time_slots)">
                                    {{ attraction.available_time_slots.join(', ') }}
                                </span>
                                <span v-else>
                                    {{ attraction.available_time_slots }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="py-16 text-center text-sm text-black dark:text-white/70"> TechPark - Um parque de
        diversões virtual
    </footer>
</template>
