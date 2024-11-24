<script setup>
import { useUserStore } from "@/stores/userStore";
import { computed, onMounted, reactive, ref, watch } from "vue";

// Initialize the user store
const userStore = useUserStore();

// Computed properties to access store data
const users = computed(() => userStore.users);
const page = computed(() => userStore.page);
const perPage = computed(() => userStore.perPage);
const headers = computed(() => userStore.headers);
const loading = computed(() => userStore.loading);
const totalItems = computed(() => userStore.totalItems);
const errorMessage = computed(() => userStore.errorMessage);

// Fetch users when component mounts
onMounted(() => {
  initializeUserTable();
});

// Method to initialize and fetch users
function initializeUserTable() {
  let dataTableParams = new URLSearchParams();
  dataTableParams.append("page", page);
  dataTableParams.append("perPage", perPage);

  console.log(perPage);
  userStore.getUsers(dataTableParams);
}

// Watch for changes in the page and fetch new data accordingly
watch(
  () => page,
  (newPage) => {
    console.log("Page changed:", newPage); // Log the new page value
    // initialize(); // Fetch users for the new page
  }
);

// Watch for changes in perPage and log the new value
watch(
  () => perPage,
  (newPerPage) => {
    console.log("Items per page changed:", newPerPage); // Log the new perPage value
    // Additional handling can be added here if needed
  }
);

function fetchUserData() {
  console.log("Inside fetchUserData");
  console.log(perPage);
}
</script>

<template>
  <v-container>
    <!-- Error message if any -->
    <v-alert v-if="errorMessage" type="error" dismissible>
      {{ errorMessage }}
    </v-alert>

    <!-- User Data Table -->
    <!-- :items-per-page="tableOptions.perPage" -->
    <!-- <v-data-table
      :headers="headers"
      :items="users"
      :items-per-page="15"
      :loading="loading"
      :server-items-length="totalItems"
      :page.sync="tableOptions.page"
      loading-text="Loading..."
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>User List</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
      </template>

      <template v-slot:item.name="{ item }">
        <span>{{ item.name }}</span>
      </template>

      <template v-slot:item.email="{ item }">
        <span>{{ item.email }}</span>
      </template>

      <template v-slot:item.phone="{ item }">
        <span>{{ item.phone }}</span>
      </template>
    </v-data-table> -->

    <v-data-table
      :headers="headers"
      :items="users"
      :items-per-page="perPage"
      @update:options="fetchUserData"
    ></v-data-table>
  </v-container>
</template>

<style scoped>
/* Add your custom styling here if needed */
</style>
