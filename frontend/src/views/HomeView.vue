<script setup>
import AppBarComponent from "../components/AppBarComponent.vue";
import LeftSideMenuComponent from "../components/LeftSideMenuComponent.vue";

// Imports from Vue
import { ref } from "vue";
import { RouterView } from "vue-router";

// reactive state
const drawer = ref(true);
</script>

<template>
  <v-responsive>
    <v-app>
      <!-- Sidebar Menu -->
      <LeftSideMenuComponent v-model="drawer" />

      <!-- Top Navbar -->
      <AppBarComponent v-model="drawer" />

      <!-- Load Dynamic Route Content -->
      <v-main>
        <v-container class="py-6 px-6" fluid>
          <RouterView />
        </v-container>
      </v-main>
    </v-app>
  </v-responsive>
</template>

<style></style>
