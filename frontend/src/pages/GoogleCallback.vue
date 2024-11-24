<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";

const router = useRouter();
const authStore = useAuthStore();
const isProcessing = ref(true); // State to track the login process

async function handleGoogleCallback() {
  const params = new URLSearchParams(window.location.search);
  const code = params.get("code");

  if (code) {
    try {
      // Send the code to the backend to finalize authentication
      const data = {
        params: { code },
      };

      await authStore.handleGoogleCallback(data);

      isProcessing.value = false;
      router.push({ name: "Dashboard" });
    } catch (error) {
      console.error("Error during Google callback:", error.response?.data || error);
    }
  } else {
    console.error("No authorization code in URL.");
  }
}

onMounted(async () => {
  await handleGoogleCallback();
});
</script>

<template>
  <v-container v-if="isProcessing" fluid class="fill-height d-flex justify-center align-center" style="background-color: #f5f5f5">
    <v-card max-width="100%" class="pa-5 d-flex align-center justify-center" elevation="10" style="border-radius: 16px; background-color: #ffffff">
      <v-row class="text-center">
        <v-col cols="12" sm="10" md="10">
          <v-img
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png"
            alt="Google Logo"
            max-width="100"
            class="mb-4"
          ></v-img>
          <v-typography variant="h5" class="font-weight-bold mb-3"> Processing Google Login... </v-typography>
          <v-progress-circular v-if="isProcessing" indeterminate color="primary" size="70" class="my-4"></v-progress-circular>
          <v-typography v-else variant="h6" class="text-success"> Login Successful! Redirecting... </v-typography>
        </v-col>
      </v-row>

      <v-row v-if="isProcessing" class="text-center">
        <v-col>
          <v-typography variant="body2" class="text-muted">
            Please wait while we log you in to your account. This may take a few seconds.
          </v-typography>
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</template>

<style scoped>
.v-card {
  max-width: 400px;
  border-radius: 16px;
}

.v-img {
  max-width: 100px;
}

.v-progress-circular {
  margin: 20px 0;
}

.text-muted {
  color: #777;
}

.text-success {
  color: #4caf50;
}
</style>
