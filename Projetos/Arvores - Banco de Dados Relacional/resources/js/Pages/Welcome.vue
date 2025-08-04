<script setup>
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
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>

    <Head title="SylvaDB" />
    <div class="bg-gray-50 text-white/50 dark:bg-white dark:text-white/50 drop-shadow">
        <header class="max-w-7xl mx-auto grid grid-cols-2 items-center gap-4 py-6 px-4 lg:grid-cols-3">

            <h1 class="ml-4 text-3xl font-extrabold text-blue-900 drop-shadow">SylvaDB</h1>

            <nav v-if="canLogin" class="col-span-1 lg:col-span-2 flex justify-end gap-8">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                Dashboard
                </Link>

                <template v-else>
                    <Link :href="route('login')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black dark:hover:text-black/80 dark:focus-visible:ring-black">
                    Entrar
                    </Link>

                    <Link v-if="canRegister" :href="route('register')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black dark:hover:text-black/80 dark:focus-visible:ring-black">
                    Cadastre-se
                    </Link>
                </template>
            </nav>
        </header>
    </div>

    <main class="mt-6">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

            <div>
                <h1 class="text-5xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                    A melhor plataforma para <br />
                    <span class="text-blue-600">organizar seus dados</span>
                </h1>

                <p class="text-lg text-gray-700 mb-8 max-w-lg">
                    Torne sua rotina mais eficiente com um banco de dados estruturado, visual e acessível para todos os
                    níveis.
                </p>

                <div class="flex flex-wrap gap-4">
                    <Link :href="canLogin ? route('login') : '#'"
                        class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Começar agora
                    </Link>

                    <a href="#"
                        class="px-6 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition">
                        Saiba mais
                    </a>
                </div>
            </div>

            <div class="flex justify-center">
                <img src="/images/Server status-rafiki.svg" alt="Pessoa usando plataforma" class="w-full max-w-lg" />
            </div>
        </div>
    </main>

    <footer class="py-16 text-center text-sm text-black dark:text-black/70">
        © Todos os direitos reservados. <strong>SylvaDB</strong>
    </footer>
</template>
