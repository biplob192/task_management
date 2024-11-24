// stores/userStore.js
import { ref } from "vue";
import { defineStore } from "pinia";
import User from "@/services/api/UserApi";

export const useUserStore = defineStore("user", () => {
  // States
  const users = ref([]); // To hold the user data
  const page = ref(1); // Current page
  const perPage = ref(5); // Default items per page
  const search = ref(""); // Search text
  const totalItems = ref(0); // To hold the total number of users
  const loading = ref(false); // To track loading state
  const errorMessage = ref(""); // Single error message
  const successMessage = ref("");

  const headers = ref([
    { title: "Name", key: "name" },
    { title: "Email", key: "email" },
    { title: "Phone", key: "phone" },
    { title: "Actions", key: "actions", sortable: false },
  ]);

  const sortBy = ref({
    key: "created_at",
    order: "desc",
  });

  const resetSortBy = () => {
    sortBy.value = {
      key: "created_at",
      order: "desc",
    };
  };

  // Actions
  // const getUsers = async (dataTableParams) => {
  const getUsers = async (dataTableParams = new URLSearchParams()) => {
    // console.log(Object.fromEntries(dataTableParams.entries()));
    // console.log(dataTableParams.toString());

    try {
      loading.value = true; // Set loading state
      const response = await User.all(dataTableParams.toString()); // Fetch users with pagination

      if (response.status === 200) {
        const data = response.data.data;

        // Assign state values
        users.value = data.data;
        page.value = data.current_page;
        perPage.value = data.per_page;
        totalItems.value = data.total;
        errorMessage.value = "";

        // Return response if needed
        return response.data;
      }
    } catch (error) {
      setErrors(error, "getUsers");
    } finally {
      loading.value = false; // Reset loading state
    }
  };

  const storeUser = async (newUserData) => {
    try {
      loading.value = true; // Set loading state
      const response = await User.store(newUserData);

      if (response.status === 201) {
        const data = response.data.data;

        // Add the new object to the beginning of the array, remove the last one
        users.value.unshift(data);
        users.value.pop();

        // Recount the total items and make empty error message
        totalItems.value += 1;
        errorMessage.value = "";

        // Return response if needed
        // return response.data;
      }
    } catch (error) {
      setErrors(error, "storeUser");
      throw error;
    } finally {
      loading.value = false; // Reset loading state
    }
  };

  const updateUser = async (id, userData) => {
    try {
      loading.value = true; // Set loading state
      const response = await User.update(id, userData);

      if (response.status === 200) {
        const updatedUser = response.data.data;

        // Find index of the user in the array and update it
        const index = users.value.findIndex((user) => user.id === id);
        if (index !== -1) {
          users.value.splice(index, 1, updatedUser);
        } else {
          throw new Error(`User with ID ${id} not found in the list`);
        }

        errorMessage.value = "";
        successMessage.value = response.data.message;

        // Return response if needed
        // return response.data;
        console.log(response.data);
      }
    } catch (error) {
      setErrors(error, "updateUser");
      throw error;
    } finally {
      loading.value = false; // Reset loading state
    }
  };

  const deleteUser = async (user) => {
    try {
      loading.value = true; // Set loading state
      const response = await User.delete(user.id);

      if (response.status === 200) {
        errorMessage.value = "";
        successMessage.value = response.data.message;

        // Return response if needed
        // return response.data;
      }
    } catch (error) {
      setErrors(error, "deleteUser");
      throw error;
    } finally {
      loading.value = false; // Reset loading state
    }
  };

  const setErrors = (error, context) => {
    // Single error message
    errorMessage.value =
      (error.status && error.status !== 422 && error.response?.data?.message) ||
      error.response?.data?.message ||
      error.message ||
      "An error occurred during " + context;

    console.error(`${context} failed:`, errorMessage.value);
  };

  return {
    users,
    totalItems,
    page,
    perPage,
    search,
    loading,
    errorMessage,
    successMessage,
    headers,
    sortBy,
    getUsers,
    storeUser,
    updateUser,
    deleteUser,
    resetSortBy,
  };
});
