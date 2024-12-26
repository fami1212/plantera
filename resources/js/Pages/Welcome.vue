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
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
      <h1 class="text-4xl font-bold text-green-700">Bienvenue sur Plantera 🌱</h1>
      <p class="text-gray-600 mt-4">
        Une plateforme moderne pour la gestion des activités agricoles.
      </p>
      <div class="mt-6 flex space-x-4">
        <a
          v-if="canLogin"
          href="/login"
          class="px-6 py-2 bg-green-700 text-white rounded hover:bg-green-600"
        >
          Connexion
        </a>
        <a
          v-if="canRegister"
          href="/register"
          class="px-6 py-2 bg-gray-200 text-green-700 rounded hover:bg-gray-300"
        >
          Inscription
        </a>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      canLogin: Boolean,
      canRegister: Boolean,
    },
  };
  </script>
  

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
