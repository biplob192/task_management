<script setup>
import { useAuthStore } from "@/stores/authStore";
import { useRouter } from "vue-router";
import { ref } from "vue";

// Reactive state
const form = ref(null);
const visible = ref(false);
const visible_c = ref(false);
const authStore = useAuthStore();
const router = useRouter();

const registrationData = ref({
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
});

// Functions
const handleRegistration = async () => {
  const formData = new FormData();
  formData.append("name", registrationData.value.name);
  formData.append("email", registrationData.value.email);
  formData.append("phone", registrationData.value.phone);
  formData.append("password", registrationData.value.password);
  formData.append(
    "password_confirmation",
    registrationData.value.password_confirmation
  );

  const { valid } = await form.value.validate(); // Validate form data

  if (valid) {
    try {
      let info = await authStore.register(formData); // Call login from the store
      let response = JSON.parse(info);

      if (response.status === 201) {
        router.push({ name: "Dashboard" }); // Navigate to the Dashboard route
      }
    } catch (e) {
      // console.log(e);
    }
  }
};
</script>

<template>
  <!-- <v-layout> -->
  <v-container>
    <v-img
      class="mx-auto my-6"
      max-width="228"
      src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
    ></v-img>

    <v-card
      class="mx-auto pa-12 pb-8"
      elevation="8"
      max-width="448"
      rounded="lg"
    >
      <v-card-title class="text-center"> User Registration </v-card-title>

      <!-- Display general error message -->
      <div v-if="authStore.errorMessage" class="text-error">
        <v-card class="mb-2 pa-5" color="red-lighten-1">
          {{ authStore.errorMessage }}
        </v-card>
      </div>

      <v-form ref="form" @submit.prevent="handleRegistration()">
        <div class="text-subtitle-1 text-medium-emphasis">Full Name</div>
        <v-text-field
          v-model="registrationData.name"
          density="compact"
          placeholder="User full name"
          prepend-inner-icon="mdi-account-outline"
          variant="outlined"
          :rules="[(v) => !!v || 'Name is required']"
          :error-messages="authStore.errorData.name || []"
        ></v-text-field>

        <div class="text-subtitle-1 text-medium-emphasis">Email</div>
        <v-text-field
          v-model="registrationData.email"
          type="email"
          density="compact"
          placeholder="Email address"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
          :rules="[(v) => !!v || 'Email is required']"
          :error-messages="authStore.errorData.email || []"
        ></v-text-field>

        <div class="text-subtitle-1 text-medium-emphasis">Phone</div>
        <v-text-field
          v-model="registrationData.phone"
          density="compact"
          placeholder="Enter your phone"
          prepend-inner-icon="mdi-phone-outline"
          variant="outlined"
          :rules="[(v) => !!v || 'Phone is required']"
          :error-messages="authStore.errorData.phone || []"
        ></v-text-field>

        <div
          class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
        >
          Password
        </div>

        <v-text-field
          v-model="registrationData.password"
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

        <div
          class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
        >
          Confirm Password
        </div>

        <v-text-field
          v-model="registrationData.password_confirmation"
          :append-inner-icon="visible_c ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible_c ? 'text' : 'password'"
          density="compact"
          placeholder="Confirm your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible_c = !visible_c"
          :rules="[(v) => !!v || 'Password confirmation is required']"
          :error-messages="authStore.errorData.password_confirmation || []"
        ></v-text-field>

        <v-btn
          type="submit"
          class="mb-4"
          color="blue"
          size="large"
          variant="tonal"
          block
        >
          Submit
        </v-btn>

        <v-card-text class="text-center">
          <RouterLink to="/login">Already have an account?</RouterLink>
        </v-card-text>
      </v-form>
    </v-card>
  </v-container>
  <!-- </v-layout> -->
</template>

<style></style>
