<script setup>
import { ref, watch } from "vue";
import { useRouter } from 'vue-router';
import { useAuthStore } from "@/stores/authStore";


// Reactive states
const router = useRouter();
const authStore = useAuthStore();
const props = defineProps({ modelValue: Boolean });
const drawer = ref(props.modelValue);
const emit = defineEmits(["update:modelValue"]);

// This will watch the drawer value has been changed in mobile view also
watch(
  () => props.modelValue,
  (newValue) => {
    drawer.value = newValue;
  }
);

// Methodes
// Emit event on drawer change
const toggleDrawer = () => {
  drawer.value = !drawer.value;
  emit("update:modelValue", drawer.value);
};

const handleLogout = async () => {
  try {
    let info = await authStore.logout(); // Call login from the store
    let response = JSON.parse(info);

    if (response.status === 200) {
      // Remove all items from localStorage
      localStorage.clear();

      // window.location.assign("/login");
      router.push({ name: "Login" });
    }
  } catch (e) {
    // console.log(e);
  }
};
</script>

<template>
  <v-app-bar border="b" class="ps-4" scroll-behavior="elevate" style="max-height: 64px">
    <v-app-bar-nav-icon v-model="drawer" @click="toggleDrawer"></v-app-bar-nav-icon>

    <v-app-bar-title>Application</v-app-bar-title>

    <template #append>
      <v-btn class="text-none me-2" height="48" icon slim>
        <v-avatar color="surface-light" image="https://biplob192.github.io/img/profile.jpg" size="32" />

        <v-menu activator="parent">
          <v-list density="compact" nav>
            <v-list-item append-icon="mdi-cog-outline" link title="Settings" />
            <v-list-item append-icon="mdi-logout" link title="Logout" @click="handleLogout" />
          </v-list>
        </v-menu>
      </v-btn>
    </template>
  </v-app-bar>
</template>

<style scoped></style>
