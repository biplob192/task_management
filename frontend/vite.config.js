import { fileURLToPath, URL } from "node:url";

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  server: {
    host: "0.0.0.0", // Listen on all interfaces
    port: 8080, // Use the desired port (default is 8080)
    strictPort: true, // Ensures it only uses the specified port (e.g., 8080)
    open: true, // Optionally, open the browser automatically
  },
});
