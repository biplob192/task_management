<script setup>
import { useTaskStore } from "@/stores/taskStore";
import { useUserStore } from "@/stores/userStore";
import { ref, watch, computed, onMounted, onUnmounted } from "vue";

// Initialize the stores
const taskStore = useTaskStore();
const userStore = useUserStore();

// Reactive properties
const users = ref([]);
const searchTask = ref("");
const searchUser = ref("");
const selectedUser = ref(null);
const selectedUsers = ref([]);
const loading = ref(false);
const page = ref(1);
const perPage = ref(100);
const menuOpen = ref(false);
const newItemDialog = ref(false);
const startDateMenu = ref(false);
const dueDateMenu = ref(false);

const newItem = ref({
  title: "",
  description: "",
  status: "to_do",
  assignedTo: null,
  assignedUsers: [],
  startDate: null,
  dueDate: null,
});

const tasks = ref({
  to_do: [],
  in_progress: [],
  in_review: [],
  complete: [],
});

const statusOptions = [
  { text: "To Do", value: "to_do" },
  { text: "In Progress", value: "in_progress" },
  { text: "In Review", value: "in_review" },
  { text: "Complete", value: "complete" },
];

// Computed properties
// const formattedStartDate = computed(() => formatDate(newItem.value.startDate));
// const formattedDueDate = computed(() => formatDate(newItem.value.dueDate));

const formattedStartDate = computed(() => {
  startDateMenu.value = false;
  return formatDate(newItem.value.startDate);
});

const formattedDueDate = computed(() => {
  dueDateMenu.value = false;
  return formatDate(newItem.value.dueDate);
});

// Functions
// const fetchTasks = async () => {
async function fetchTasks() {
  // console.log("Inside fetchTasks");

  try {
    let dataTableParams = new URLSearchParams();
    dataTableParams.append("search", searchTask.value ?? "");

    // if (search != "") {
    //   dataTableParams.append("search", search.value);
    // }

    const response = await taskStore.getTasks(dataTableParams);

    tasks.value = {
      to_do: response.data.data.filter((task) => task.status === "to_do"),
      in_progress: response.data.data.filter((task) => task.status === "in_progress"),
      in_review: response.data.data.filter((task) => task.status === "in_review"),
      complete: response.data.data.filter((task) => task.status === "complete"),
    };
  } catch (error) {
    console.error("Error fetching task data:", error);
  }
}

// async function fetchUsers() {
//   // Search only when the key length is enough
//   if ((userStore.search != null && userStore.search != "" && userStore.search.length <= 2) || userStore.perPage == -2) {
//     return false;
//   }

//   try {
//     let dataTableParams = new URLSearchParams();
//     dataTableParams.append("page", userStore.page);
//     // dataTableParams.append("perPage", userStore.perPage);
//     dataTableParams.append("perPage", -1);
//     dataTableParams.append("search", userStore.search ?? "");
//     dataTableParams.append("sort_key", userStore.sortBy.key);
//     dataTableParams.append("sort_order", userStore.sortBy.order);

//     const response = await userStore.getUsers(dataTableParams);
//     users.value = response.data.data.map((user) => ({ text: user.name, value: user.id }));

//     // console.log(users.value);
//   } catch (error) {
//     console.error("Error fetching user data:", error);
//   }
// }

// Function to handle form submission for new item

// Fetch users for the initial load or search

const fetchUsers = async (reset = false) => {
  // console.log("Inside fetchUsers");
  if (reset) {
    users.value = [];
    page.value = 1;
  }
  loading.value = true;
  try {
    let dataTableParams = new URLSearchParams();
    dataTableParams.append("page", page.value);
    dataTableParams.append("perPage", 20);
    dataTableParams.append("search", searchUser.value ?? "");

    // Search only when the key length is enough
    if (searchUser.value != null && searchUser.value != "" && searchUser.value.length <= 2) {
      return false;
    }

    const response = await userStore.getUsers(dataTableParams);
    if (!response || !response.data || !response.data.data) {
      console.error("Unexpected response format:", response);
      return;
    }
    // const data = response.data.data.map((user) => ({ id: user.id, text: user.name, value: user.id, label: user.name }));
    const data = response.data?.data?.map((user) => ({ id: user.id, text: user.name, value: user.id, label: user.name })) || [];

    users.value = reset ? data : [...users.value, ...data];
    page.value += 1;
  } finally {
    loading.value = false;
  }
};

// On click New Item btn
const openNewItemDialog = () => {
  newItemDialog.value = true;
};

const createNewItem = async () => {
  // console.log("Creating new item with data:", newItem.value);

  // Prepare an array of user IDs from `assignedUsers`
  const userIds = newItem.value.assignedUsers[0].map((user) => user.id);

  let formData = new FormData();
  formData.append("title", newItem.value.title);
  formData.append("description", newItem.value.description);
  formData.append("status", newItem.value.status);
  // formData.append("assigned_to", newItem.value.assignedTo);
  userIds.forEach((item, index) => {
    formData.append(`assigned_users[]`, item);
  });

  formData.append("start_date", formattedStartDate.value);
  formData.append("due_date", formattedDueDate.value);

  try {
    // Call your API to create a new task
    await taskStore.storeTask(formData);
    fetchTasks();

    // Reset the form fields
    newItem.value = {
      title: "",
      description: "",
      status: "to_do",
      assignedTo: null,
      assignedUsers: [],
      startDate: null,
      dueDate: null,
    };
    selectedUser.value = null;
    selectedUsers.value = null;
  } catch (error) {
    // console.error("Error creating task:", error);
  } finally {
    // Close dialog after creation
    newItemDialog.value = false;
  }
};

const moveTask = (task, newStatus) => {
  updateTask(task.id, newStatus);
};

const updateTask = async (taskId, newStatus) => {
  let formData = new FormData();
  formData.append("status", newStatus);
  formData.append("_method", "PUT");

  try {
    // Call your API to create a new user
    const response = await taskStore.updateTask(taskId, formData);

    await fetchTasks();
  } catch (error) {
    // console.error("Error updating TaskStatus:", error);
  }
};

// Function to format the date
const formatDate = (date) => {
  if (!date) return "";
  const options = { year: "numeric", month: "short", day: "2-digit", timeZone: "Asia/Dhaka" };

  const d = new Date(date);
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, "0"); // Months are 0-based
  const day = String(d.getDate()).padStart(2, "0");

  return `${year}-${month}-${day}`;
};

function setSelected(value) {
  // console.log("Inside setSelected");

  // For single selected user
  // newItem.value.assignedTo = value.id;

  // Add the selected users to the `assignedUsers` array
  newItem.value.assignedUsers = value ? [value] : [];
}

function searchForUser(value) {
  // console.log("Inside searchForUser");

  searchUser.value = value;
  fetchUsers(true);
}

// Watchers
// watch(searchUser, () => fetchUsers(true));

// onMounted(() => {
//   // fetchUsers();

//   fetchTasks();
//   fetchUsers(true);
// });

onMounted(async () => {
  // Call fetchUsers first, and if successful, proceed to fetchTasks
  await fetchTasks();
  await fetchUsers(true);
});
</script>

<template>
  <v-row>
    <v-col class="d-flex" cols="12" md="12">
      <v-card width="100%">
        <v-row class="d-flex align-center mt-0 mb-2 pa-3 bg-grey-lighten-3">
          <!-- <v-col> -->
          <v-card-title>Task Management Board</v-card-title>
          <!-- <v-card-subtitle>Manage all your task with status and timeline.</v-card-subtitle> -->
          <!-- </v-col> -->

          <v-spacer class="d-none d-sm-flex"></v-spacer>

          <!-- <v-col class="d-flex justify-end align-center"> -->
          <v-text-field
            v-model="searchTask"
            label="Search"
            append-inner-icon="mdi-magnify"
            clearable
            hide-details
            variant="outlined"
            density="compact"
            class="ml-5 mr-5"
            @blur="fetchTasks"
          ></v-text-field>
          <v-btn class="mr-5" prepend-icon="mdi-list-status" variant="text" @click="openNewItemDialog"> New Task </v-btn>
          <!-- </v-col> -->
        </v-row>

        <v-card-text class="px-5">
          <v-row>
            <v-col v-for="(taskList, status) in tasks" :key="status" cols="12" sm="6" md="3">
              <v-card>
                <v-card-title>{{ status.replace("_", " ").toUpperCase() }}</v-card-title>

                <v-divider></v-divider>
                <v-list max-height="62vh">
                  <!-- Display each task -->
                  <v-list-item
                    v-for="task in taskList"
                    :key="task.id"
                    draggable="true"
                    @dragstart="draggedTask = task"
                    @dragover.prevent
                    @drop="moveTask(draggedTask, status)"
                  >
                    <v-card class="my-2" width="100%" :title="task.title" :subtitle="task.description" color="grey-lighten-4">
                      <!-- <v-card-title>{{ task.title }}</v-card-title> -->
                      <!-- <v-card-subtitle>{{ task.description }}</v-card-subtitle> -->
                      <!-- <v-card-text>{{ task.description }}</v-card-text> -->
                      <v-card-text>Due: {{ task.due_date }}</v-card-text>
                    </v-card>
                  </v-list-item>

                  <!-- Placeholder drop target when the list is empty -->
                  <v-sheet
                    v-if="taskList.length === 0"
                    class="empty-drop-target ma-4"
                    height="100px"
                    color="grey lighten-3"
                    outlined
                    rounded
                    @dragover.prevent
                    @drop="moveTask(draggedTask, status)"
                  >
                    <v-row class="ma-0 fill-height d-flex align-center justify-center">
                      <span>Drop task here</span>
                    </v-row>
                  </v-sheet>
                </v-list>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>

  <!-- New Item Dialog -->
  <v-dialog v-model="newItemDialog" persistent max-width="500">
    <v-card>
      <v-card-title class="headline bg-grey-lighten-3 d-flex justify-space-between align-center">
        Create New Item

        <v-btn variant="text" @click="newItemDialog = false">Close</v-btn>
      </v-card-title>
      <v-card-text>
        <!-- {{ newItem.startDate.toISOString().slice(0, 10) }} -->
        <form @submit.prevent="createNewItem">
          <v-text-field variant="underlined" label="Title" v-model="newItem.title" required></v-text-field>

          <v-textarea variant="underlined" label="Description" v-model="newItem.description" required></v-textarea>

          <!-- Status Selector -->
          <v-select
            variant="underlined"
            label="Status"
            :items="statusOptions"
            item-title="text"
            item-value="value"
            v-model="newItem.status"
            required
          />

          <!-- @input="searchForUser" -->
          <v-select2
            multiple
            class="custom-select"
            v-model="selectedUsers"
            :options="users"
            placeholder="Select user"
            :searchable="true"
            @search="searchForUser"
            @option:selected="setSelected"
          />

          <!-- Start Date Picker -->
          <v-menu v-model="startDateMenu" :close-on-content-click="false" :open-on-click="true" transition="scale-transition" offset-y>
            <template #activator="{ props }">
              <!-- Use props instead of destructuring on -->
              <v-text-field variant="underlined" v-model="formattedStartDate" label="Start Date" readonly v-bind="props"></v-text-field>
            </template>
            <v-date-picker v-model="newItem.startDate" @input="startDateMenu = false"></v-date-picker>
          </v-menu>

          <!-- Due Date Picker -->
          <v-menu v-model="dueDateMenu" :close-on-content-click="false" :open-on-click="true" transition="scale-transition" offset-y>
            <template #activator="{ props }">
              <!-- Use props instead of destructuring on -->
              <v-text-field variant="underlined" v-model="formattedDueDate" label="Due Date" readonly v-bind="props"></v-text-field>
            </template>
            <v-date-picker v-model="newItem.dueDate" @input="dueDateMenu = false"></v-date-picker>
          </v-menu>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" color="primary">Submit</v-btn>
          </v-card-actions>
        </form>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<style scoped>
[v-cloak] {
  display: none;
}

/* Custom CSS to match Vuetify underlined styles */
.custom-select :deep(.vs__search) {
  padding: 0;
}

.custom-select :deep(.vs__dropdown-toggle) {
  padding: 8px 0px 4px 0px;
  margin-bottom: 18px;
  background-color: transparent;
  height: auto;
  border: 0;
  border-bottom: 1px solid gray;
  border-radius: 0;
  color: gray;
}

.custom-select :deep(.vs__selected-options) {
  padding-left: 0px;
}

.custom-select :deep(.vs__selected) {
  padding-left: 0px;
}
</style>
