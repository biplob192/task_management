<script setup>
import Dashboard from "../views/DashboardView.vue";

// Imports from Vue
import { useRoute } from "vue-router";

// Reactive state
const route = useRoute();
</script>

<template>
  <v-main>
    <v-container class="py-6 px-6" fluid>
      <!-- <Dashboard v-if="route.name === 'Home'" /> -->

      <!-- Render the current route content here -->
      <RouterView />
    </v-container>
  </v-main>
</template>

<style scoped></style>
