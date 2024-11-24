<script setup>
import { ref, onMounted, watch } from "vue";

// reactive state
const userRole = ref("");
const userEmail = ref("");
const scrollbarVisible = ref(false);
const props = defineProps({ modelValue: Boolean });
const drawer = ref(props.modelValue);
const emit = defineEmits(["update:modelValue"]);

const dashboardMenuitems = ref([
  {
    title: "Dashboard",
    prependIcon: "mdi-view-dashboard-outline",
    to: "/",
    activeClass: "active",
  },
]);

const menuItems = ref([
  {
    title: "Users",
    prependIcon: "mdi-account-group",
    to: "/users",
  },
  // {
  //   title: "Team",
  //   prependIcon: "mdi-account-group",
  //   to: "/team",
  // },
  // {
  //   title: "Projects",
  //   prependIcon: "mdi-briefcase-outline",
  //   to: "/projects",
  // },
  {
    title: "Tasks",
    prependIcon: "mdi-list-status",
    to: "/tasks",
  },
  // {
  //   title: "List",
  //   prependIcon: "mdi-format-list-bulleted",
  //   to: "/list",
  // },
]);

// functions to show and hide the scrollbar
const showScrollbar = () => {
  scrollbarVisible.value = true;
};

const hideScrollbar = () => {
  scrollbarVisible.value = false;
};

onMounted(() => {
  // Retrieve the user_info from localStorage and parse it
  const userInfo = JSON.parse(localStorage.getItem("user_info"));

  userRole.value = localStorage.getItem("user_role") || "Guest"; // Default if not set

  // Check if userInfo exists and has an email
  if (userInfo && userInfo.email) {
    userEmail.value = userInfo.email;
  }
});

watch(
  () => props.modelValue,
  (newValue) => {
    drawer.value = newValue;
  }
);
</script>

<template>
  <v-navigation-drawer
    v-model="drawer"
    @mouseenter="showScrollbar"
    @mouseleave="hideScrollbar"
    :class="!scrollbarVisible ? 'hide-scrollbar' : ''"
    @update:modelValue="emit('update:modelValue', drawer)"
  >
    <!-- User Profile Section -->
    <v-sheet class="pa-4" color="grey-lighten-2">
      <v-row class="d-flex align-center flex-nowrap ma-0" style="overflow: hidden; text-overflow: ellipsis">
        <!-- Profile Avatar -->
        <v-avatar color="grey-darken-1" image="https://biplob192.github.io/img/profile.jpg" size="64"></v-avatar>

        <!-- User Role and Email stacked vertically -->
        <div class="ml-2 d-flex flex-column">
          <span class="text-bold">{{ userRole }}</span>
          <span class="text-caption mr-2">{{ userEmail }}</span>
        </div>
      </v-row>
    </v-sheet>

    <v-divider></v-divider>

    <!-- Menu List Start -->
    <!-- Dashboard Menu -->
    <v-list density="compact" item-props :items="dashboardMenuitems" nav />

    <v-list-item title="" subtitle="Menus"></v-list-item>
    <v-divider></v-divider>

    <!-- Others Menu Links -->
    <v-list density="compact" item-props :items="menuItems" nav />

    <!-- Additional Links Section -->
    <template #append>
      <v-sheet class="pt-2 pb-2" color="grey-lighten-2">
        <v-list-item class="ma-2" link nav prepend-icon="mdi-cog-outline" title="Settings" to="#" />
      </v-sheet>
    </template>
  </v-navigation-drawer>
</template>

<style scoped></style>
