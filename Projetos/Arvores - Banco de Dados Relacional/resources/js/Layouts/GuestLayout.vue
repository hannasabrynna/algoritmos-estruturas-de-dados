<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-100 via-blue-300 to-blue-500 pt-6 sm:justify-center sm:pt-0">

        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
            <div class="flex justify-center m-4 font-bold text-3xl text-blue-900 px-6 py-4">
                <Link href="/">
                <h1>Bem-vindo ao SylvaDB!</h1>
                </Link>
            </div>
            <slot />
        </div>
    </div>
</template>
