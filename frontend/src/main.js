// Importing the Vue Instance
import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import "./assets/css/style.css";
import router from "./router";

// Importing Vue-Select
import VSelect2 from "vue-select";
import "vue-select/dist/vue-select.css";

// Importing Vuetify
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import { aliases, mdi } from "vuetify/iconsets/mdi";

// Creating Vuetify instance
const vuetify = createVuetify({
  // ssr: true,
  components,
  directives,
  icons: {
    defaultSet: "mdi",
    aliases,
    sets: {
      mdi,
    },
  },
});

// Creating Pinia instance
const pinia = createPinia();

// Creating Vue application instance
const app = createApp(App);

// Register vue-select globally
app.component("VSelect2", VSelect2);

// Using plugins in the application
app.use(pinia); // Pinia for state management
app.use(vuetify); // Vuetify for UI components
app.use(router); // Vue Router for routing

// Mounting the application
app.mount("#app");
