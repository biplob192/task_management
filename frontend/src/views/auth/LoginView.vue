<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/authStore";

// reactive state
const form = ref(null);
const visible = ref(false);
const authStore = useAuthStore();
const router = useRouter();

const loginData = ref({
  email: "",
  password: "",
});

const allErrors = computed(() => {
  return Object.values(authStore.errorData).flat();
});

// Functions
const handleLogin = async () => {
  const formData = new FormData();
  formData.append("email", loginData.value.email);
  formData.append("password", loginData.value.password);

  const { valid } = await form.value.validate(); // Validate form data

  if (valid) {
    try {
      let info = await authStore.login(formData); // Call login from the store
      let response = JSON.parse(info);

      if (response.status === 200) {
        router.push({ name: "Dashboard" }); // Navigate to the Home route
      }
    } catch (e) {
      // console.log(e);
    }
  }
};

const handleGoogleLogin = async () => {
  console.log("handleGoogleLogin");

  try {
    // Call the backend API to get the Google redirect URL
    const response = await authStore.loginWithGoogle();

    // Redirect the user to Google's authentication page
    const googleLoginUrl = response.data.url;
    window.location.href = googleLoginUrl;
  } catch (e) {
    console.log(e);
  }
};
</script>

<template>
  <!-- <v-layout> -->
  <v-container>
    <v-img class="mx-auto my-6" max-width="228" src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"></v-img>

    <v-card class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg">
      <!-- Display general error message -->
      <div v-if="authStore.errorMessage" class="text-error">
        <v-card class="mb-2 pa-5" color="red-lighten-1">
          {{ authStore.errorMessage }}
        </v-card>
      </div>

      <!-- Display specific field errors -->
      <!-- <div v-if="allErrors.length" class="text-error">
        <ul>
          <li v-for="(error, index) in allErrors" :key="index">{{ error }}</li>
        </ul>
      </div> -->

      <!-- <div v-if="Object.keys(authStore.errorData).length" class="text-error">
        <ul>
          <li v-for="(errors, field) in authStore.errorData" :key="field">
            <strong>{{ field }}:</strong>
            <ul>
              <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
            </ul>
          </li>
        </ul>
      </div> -->

      <v-form ref="form" @submit.prevent="handleLogin()">
        <div class="text-subtitle-1 text-medium-emphasis">Account</div>

        <v-text-field
          v-model="loginData.email"
          type="email"
          density="compact"
          placeholder="Email address"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
          :rules="[(v) => !!v || 'Email is required']"
          :error-messages="authStore.errorData.email || []"
        ></v-text-field>

        <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
          Password

          <a class="text-caption text-decoration-none text-blue" href="#" rel="noopener noreferrer" target="_blank"> Forgot login password?</a>
        </div>

        <v-text-field
          v-model="loginData.password"
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible = !visible"
          :rules="[(v) => !!v || 'Password is required']"
          :error-messages="authStore.errorData.password || []"
        ></v-text-field>

        <v-card class="mb-12" color="surface-variant" variant="tonal">
          <v-card-text class="text-medium-emphasis text-caption">
            Warning: After 3 consecutive failed login attempts, you account will be temporarily locked for three hours. If you must login now, you can
            also click "Forgot login password?" below to reset the login password.
          </v-card-text>
        </v-card>

        <v-btn type="submit" class="mb-2" color="blue" size="large" variant="tonal" block> Log In </v-btn>

        <v-btn prepend-icon="mdi mdi-google" class="mb-4" color="red" variant="text" block @click="handleGoogleLogin()"> Sign in with Google </v-btn>

        <v-card-text class="text-center">
          <RouterLink to="/register">Have no account?</RouterLink>
        </v-card-text>
      </v-form>
    </v-card>
  </v-container>
  <!-- </v-layout> -->
</template>

<style></style>
