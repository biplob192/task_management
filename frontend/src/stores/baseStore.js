// stores/baseStore.js

import { defineStore } from "pinia";
import { ref } from "vue";

export const useBaseStore = defineStore("base", () => {
  // State
  const baseUrl = ref("http://127.0.0.1:8000/api/");
  const userInfo = ref(null);

  // Optional actions or getters can be added here if needed
  return {
    baseUrl,
    userInfo,
  };
});
