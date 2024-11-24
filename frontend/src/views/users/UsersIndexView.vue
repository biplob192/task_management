<script setup>
import { useUserStore } from "@/stores/userStore";
import { computed, onMounted, reactive, ref, watch } from "vue";

// Initialize the user store
const userStore = useUserStore();

// Computed property for the success and error message from the store
const errorMessage = computed(() => userStore.errorMessage);
const successMessage = computed(() => userStore.successMessage);

const dialogTitle = computed(() => {
  return dialogAction.value === "delete" ? "Delete" : "Edit";
});

// Component reactive properties
const showSnackbar = ref(false);
const snackbarTimeout = ref(3000);
let timeoutId = null;

const perPage = ref(5);

// Dialog and form data for "New Item"
const newItemDialog = ref(false);
const newItem = ref({
  name: "",
  email: "",
  phone: "",
  password: "",
  passwordConfirmation: "",
});

// Dialog and form data for "Edit Item"
const editItemDialog = ref(false);
const editItem = ref({
  id: null,
  name: "",
  email: "",
  phone: "",
  password: "",
  passwordConfirmation: "",
});

// Edit or delete related properties
const dialog = ref(false);
const dialogAction = ref("");
const selectedItem = ref(null);

// On click New Item btn
const openNewItemDialog = () => {
  newItemDialog.value = true;
};

// Open "Edit Item" dialog and populate with item data
const openEditItemDialog = (item) => {
  editItem.value = {
    id: item.id,
    name: item.name,
    email: item.email,
    phone: item.phone,
    password: "",
    passwordConfirmation: "",
  };
  editItemDialog.value = true;
};

// Function to handle form submission for new item
const createNewItem = async () => {
  // console.log("Creating new item with data:", newItem.value);

  let formData = new FormData();
  formData.append("name", newItem.value.name);
  formData.append("email", newItem.value.email);
  formData.append("phone", newItem.value.phone);
  formData.append("password", newItem.value.password);
  formData.append("password_confirmation", newItem.value.passwordConfirmation);

  try {
    // Call your API to create a new user
    const response = await userStore.storeUser(formData);

    // Reset the form fields
    newItem.value = {
      name: "",
      email: "",
      phone: "",
      password: "",
      passwordConfirmation: "",
    };
  } catch (error) {
    // console.error("Error creating user:", error);
  } finally {
    // Close dialog after creation
    newItemDialog.value = false;
  }
};

// Handle form submission for updating item
const updateItem = async (id) => {
  // console.log("Updating item with data:", editItem.value);
  // console.log(id);

  let formData = new FormData();
  formData.append("name", editItem.value.name);
  formData.append("email", editItem.value.email);
  formData.append("phone", editItem.value.phone);
  // formData.append("password", editItem.value.password);
  // formData.append("password_confirmation", editItem.value.passwordConfirmation);
  formData.append("_method", "PUT");

  // Conditionally append password and password confirmation if both are not empty
  if (editItem.value.password && editItem.value.passwordConfirmation) {
    formData.append("password", editItem.value.password);
    formData.append("password_confirmation", editItem.value.passwordConfirmation);
  }

  try {
    // Call your API to create a new user
    const response = await userStore.updateUser(id, formData);

    // Reset the form fields
    editItem.value = {
      id: null,
      name: "",
      email: "",
      phone: "",
      password: "",
      passwordConfirmation: "",
    };
  } catch (error) {
    // console.error("Error updating user:", error);
  } finally {
    // Close dialog after updating
    editItemDialog.value = false;
  }
};

// Fetch user data through API when initialize the table
async function fetchUserData() {
  // console.log("Inside fetchUserData");
  // Search only when the key length is enough
  if ((userStore.search != null && userStore.search != "" && userStore.search.length <= 2) || userStore.perPage == -2) {
    return false;
  }

  try {
    let dataTableParams = new URLSearchParams();
    dataTableParams.append("page", userStore.page);
    // dataTableParams.append("perPage", userStore.perPage);
    dataTableParams.append("perPage", perPage.value);
    dataTableParams.append("search", userStore.search ?? "");
    dataTableParams.append("sort_key", userStore.sortBy.key);
    dataTableParams.append("sort_order", userStore.sortBy.order);

    await userStore.getUsers(dataTableParams);
  } catch (error) {
    // console.error("Error fetching user data:", error);
  }
}

// Method to handle page changes
function onPageChange(newPage) {
  userStore.page = newPage;
}

// Method to handle sort by
function onSortBy(newSortBy) {
  if (newSortBy.length > 0 && newSortBy[0]) {
    userStore.sortBy.key = newSortBy[0].key;
    userStore.sortBy.order = newSortBy[0].order;
  } else {
    userStore.resetSortBy();
  }
}

// On click edit item
const confirmEditItem = (item) => {
  dialogAction.value = "edit";
  selectedItem.value = item;
  dialog.value = true;
};

// On click delete item
const confirmDeleteItem = (item) => {
  dialogAction.value = "delete";
  selectedItem.value = item;
  dialog.value = true;
};

// This will run after confirm
const confirmAction = () => {
  if (dialogAction.value === "delete") {
    confirmedDeleteItem(selectedItem.value);
  } else if (dialogAction.value === "edit") {
    confirmedEditItem(selectedItem.value);
  }

  dialog.value = false;
  dialogAction.value = "";
  selectedItem.value = null;
};

async function confirmedDeleteItem(item) {
  try {
    // Delete item
    await userStore.deleteUser(item);

    // Fetch update item list
    fetchUserData();
  } catch (error) {
    console.error("Error deleting user:", error);
  }
}

const confirmedEditItem = (item) => {
  openEditItemDialog(item);
};

// Watch for changes in the success message to trigger the toast
watch(successMessage, (newMessage) => {
  if (newMessage) {
    showSnackbar.value = true;
    // setTimeout(() => (userStore.successMessage = ""), 4000);

    // Start the snackbar timeout
    startSnackbarTimeout();
  }
});

// Function to start the snackbar timeout
const startSnackbarTimeout = () => {
  clearTimeout(timeoutId); // Clear any existing timeout
  timeoutId = setTimeout(() => {
    userStore.successMessage = ""; // Clear the success message after display
    showSnackbar.value = false; // Hide snackbar
  }, snackbarTimeout.value);
};

// Pause the snackbar on mouse hover
const pauseSnackbar = () => {
  clearTimeout(timeoutId); // Clear the timeout to pause
};

// Resume the snackbar timeout on mouse leave
const resumeSnackbar = () => {
  startSnackbarTimeout(); // Restart the timeout
};

// Fetch users when component mounts
onMounted(() => {});
</script>

<template>
  <v-row>
    <!-- Snackbar toast notification -->
    <template>
      <v-snackbar
        v-model="showSnackbar"
        color="green"
        location="top"
        class="v-snackbar--right"
        @mouseenter="pauseSnackbar"
        @mouseleave="resumeSnackbar"
        :timeout="snackbarTimeout"
      >
        {{ userStore.successMessage }}
      </v-snackbar>
    </template>

    <!-- Error alert notification -->
    <v-col v-if="errorMessage" class="d-flex" cols="12" md="12">
      <v-alert type="error">
        {{ errorMessage }}
        <template v-slot:append>
          <v-icon @click="userStore.errorMessage = ''" class="ml-2" style="cursor: pointer"> mdi-close </v-icon>
        </template>
      </v-alert>
    </v-col>

    <v-col class="d-flex" cols="12" md="12">
      <v-card width="100%">
        <!-- <v-card-title>This is Project Page</v-card-title> -->
        <!-- <v-card-subtitle>Card subtitle</v-card-subtitle> -->

        <!-- @update:page="onPageChange" -->
        <!-- v-model:page="userStore.page" -->
        <!-- v-model:items-per-page="userStore.perPage" -->
        <v-data-table-server
          :items="userStore.users"
          :items-length="userStore.totalItems"
          :headers="userStore.headers"
          :loading="userStore.loading"
          :search="userStore.search"
          loading-text="Loading..."
          class="elevation-1"
          hover
          v-model:items-per-page="perPage"
          @update:page="onPageChange"
          @update:sortBy="onSortBy"
          @update:options="fetchUserData"
        >
          <!-- Start adding slots to the table  -->
          <template v-slot:top>
            <v-toolbar flat>
              <v-toolbar-title class="d-none d-sm-flex">User List</v-toolbar-title>

              <v-spacer class="d-none d-sm-flex"></v-spacer>

              <v-text-field
                v-model="userStore.search"
                label="Search"
                append-inner-icon="mdi-magnify"
                clearable
                hide-details
                variant="outlined"
                density="compact"
                class="ml-5 mr-5"
              ></v-text-field>

              <!-- <v-btn class="mb-2" color="primary" dark> New Item </v-btn> -->
              <v-btn class="mr-5" prepend-icon="mdi-account-plus" variant="outlined" @click="openNewItemDialog"> New Item </v-btn>
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

          <template v-slot:item.actions="{ item }">
            <v-icon class="me-2" size="small" @click="confirmEditItem(item)"> mdi-pencil </v-icon>
            <v-icon size="small" @click="confirmDeleteItem(item)"> mdi-delete </v-icon>
          </template>

          <template v-slot:no-data>
            <v-btn color="primary" @click="fetchUserData"> Reset </v-btn>
          </template>
          <!-- End adding slots to the table  -->
        </v-data-table-server>
      </v-card>
    </v-col>
  </v-row>

  <!-- Persistent Dialog for Edit/Delete Confirmation -->
  <v-dialog v-model="dialog" persistent max-width="500" dismissible>
    <v-card>
      <v-card-title class="headline bg-grey-lighten-3 d-flex justify-space-between align-center">
        Confirm
        {{ dialogTitle }}

        <!-- <span @click="dialog = false" style="cursor: pointer; margin-left: auto">X</span> -->
      </v-card-title>
      <!-- <v-card-text> Are you sure you want to {{ dialogAction }} this item? </v-card-text> -->
      <v-card-text>
        Are you sure you want to
        {{ dialogAction }}
        <span style="font-weight: bold">{{ selectedItem?.name || "this item" }}</span
        >?
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red darken-1" text @click="dialog = false">No</v-btn>
        <v-btn color="green darken-1" text @click="confirmAction">Yes</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- New Item Dialog -->
  <v-dialog v-model="newItemDialog" persistent max-width="500">
    <v-card>
      <v-card-title class="headline bg-grey-lighten-3 d-flex justify-space-between align-center">
        Create New Item
        <!-- <span @click="newItemDialog = false" style="cursor: pointer; margin-left: auto">X</span> -->
        <v-btn variant="text" @click="newItemDialog = false">Close</v-btn>
      </v-card-title>
      <v-card-text>
        <form @submit.prevent="createNewItem">
          <v-text-field variant="underlined" label="Full Name" v-model="newItem.name" required></v-text-field>

          <v-text-field variant="underlined" label="Email" v-model="newItem.email" type="email" required></v-text-field>

          <v-text-field variant="underlined" label="Phone" v-model="newItem.phone" required></v-text-field>

          <v-text-field variant="underlined" label="Password" v-model="newItem.password" type="password" required></v-text-field>

          <v-text-field variant="underlined" label="Confirm Password" v-model="newItem.passwordConfirmation" type="password" required></v-text-field>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" color="primary">Submit</v-btn>
          </v-card-actions>
        </form>
      </v-card-text>
    </v-card>
  </v-dialog>

  <!-- Edit Item Dialog -->
  <v-dialog v-model="editItemDialog" persistent max-width="500">
    <v-card>
      <v-card-title class="headline bg-grey-lighten-3 d-flex justify-space-between align-center">
        Edit Item

        <v-btn variant="text" @click="editItemDialog = false">Close</v-btn>
        <!-- <span
          @click="editItemDialog = false"
          style="cursor: pointer; font-weight: bold; margin-left: auto"
          >X</span
        > -->
      </v-card-title>
      <v-card-text>
        <form @submit.prevent="updateItem(editItem.id)">
          <v-text-field variant="underlined" label="Full Name" v-model="editItem.name" required></v-text-field>

          <v-text-field variant="underlined" label="Email" v-model="editItem.email" type="email" required></v-text-field>

          <v-text-field variant="underlined" label="Phone" v-model="editItem.phone" required></v-text-field>

          <v-text-field variant="underlined" label="Password" v-model="editItem.password" type="password"></v-text-field>

          <v-text-field variant="underlined" label="Password Confirmation" v-model="editItem.passwordConfirmation" type="password"></v-text-field>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="submit" color="primary">Save Changes</v-btn>
          </v-card-actions>
        </form>
      </v-card-text>
    </v-card>
  </v-dialog>

  <v-container> </v-container>
</template>

<style scoped></style>
