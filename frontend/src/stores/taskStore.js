// stores/taskStore.js
import { ref } from "vue";
import { defineStore } from "pinia";
import Task from "@/services/api/TaskApi";

export const useTaskStore = defineStore("task", () => {
  // States
  const tasks = ref([]); // To hold the task data
  const errorMessage = ref("");
  const successMessage = ref("");

  // Actions
  // const getTasks = async () => {
  const getTasks = async (dataTableParams = new URLSearchParams()) => {
    // console.log(dataTableParams.toString());
    try {
      const response = await Task.all(dataTableParams.toString());

      if (response.status === 200) {
        const data = response.data.data;

        // Assign state values
        tasks.value = data.data;
        errorMessage.value = "";

        // Return response if needed
        return response.data;
      }
    } catch (error) {
      setErrors(error, "getTasks");
    } finally {
    }
  };

  const updateTask = async (id, data) => {
    try {
      const response = await Task.update(id, data);

      if (response.status === 200) {
        const updatedTask = response.data.data;

        errorMessage.value = "";
        successMessage.value = response.data.message;

        // Return response if needed
        return response.data;
        console.log(response.data);
      }
    } catch (error) {
      setErrors(error, "updateTask");
      throw error;
    }
  };

  const storeTask = async (data) => {
    try {
      const response = await Task.store(data);

      if (response.status === 201) {
        const data = response.data.data;
        // console.log(data);

        errorMessage.value = "";
        successMessage.value = response.data.message;

        // Return response if needed
        return response.data;
        console.log(response.data);
      }
    } catch (error) {
      setErrors(error, "updateUser");
      throw error;
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
    tasks,
    errorMessage,
    successMessage,
    getTasks,
    storeTask,
    updateTask,
  };
});
