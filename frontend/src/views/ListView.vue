<script setup>
import { ref, computed, onMounted } from "vue";
import { useUserStore } from "@/stores/userStore";

// Initialize the stores
const userStore = useUserStore();

// Sample data for demonstration
const users = ref([]); // Will hold user options
const selectedUsers = ref([]); // Will hold selected users
const selectedUser = ref(null); // Will hold selected users
const loading = ref(false);

const user = computed(() => selectedUsers);

// Fetch users data from your backend
// const fetchUsers = async () => {
//   const response = await fetch("/api/users"); // Adjust the endpoint as necessary
//   const data = await response.json();
//   options.value = data.map((user) => ({ id: user.id, text: user.name })); // Adjust as per your API response
// };

const fetchUsers = async (reset = false) => {
  console.log("Inside fetchUsers");
  if (reset) {
    users.value = [];
    page.value = 1;
  }
  loading.value = true;
  try {
    let dataTableParams = new URLSearchParams();
    // dataTableParams.append("page", page.value);
    dataTableParams.append("perPage", 20);
    // dataTableParams.append("search", searchUser.value ?? "");

    const response = await userStore.getUsers(dataTableParams);
    // console.log(response.data.data);
    // const data = response.data.data.map((user) => ({ text: user.name, value: user.id }));
    const data = response.data.data.map((user) => ({ id: user.id, text: user.name, label: user.name }));
    // console.log(data);

    users.value = reset ? data : [...users.value, ...data];
    // console.log(users.value);
    // page.value += 1;
  } finally {
    loading.value = false;
  }
};

function setSelected(value) {
  console.log("Inside setSelected");
  console.log(value);
}

onMounted(() => {
  fetchUsers();
});
</script>

<template>
  <div>
    {{ selectedUsers }}
    {{ selectedUser }}
    <!-- <select2 v-model="selectedUsers" :options="users" multiple :searchable="true" /> -->
    <!-- <select2 multiple v-model="selectedUsers" :options="['Canada', 'United States']" label="text" /> -->
    <!-- <select2 v-model="selectedUsers" :options="users" label="text" placeholder="Select user" multiple :searchable="true" /> -->
    <!-- <v-select v-model="selectedUser" :options="users" label="text" placeholder="Select user" :searchable="true" /> -->

    <!-- <v-select
      multiple
      v-model="selectedUsers"
      :options="users"
      @input="setSelected"
      @select="setSelected"
      @option:selected="setSelected"
      placeholder="Select user"
      :searchable="true"
      :selectable="() => selectedUsers.length < 3"
    /> -->

    <v-select v-model="selectedUser" :options="users" @option:selected="setSelected" placeholder="Select user" :searchable="true" />
    <v-select2
      v-model="selectedUser"
      :options="users"
      @option:selected="setSelected"
      @input="setSelected"
      placeholder="Select user"
      :searchable="true"
      class="custom-select"
    />
  </div>
</template>

<style scoped>
/* .custom-select ::v-deep .vs__dropdown-toggle { */
.custom-select :deep(.vs__dropdown-toggle) {
  min-height: 45px;
}
</style>
