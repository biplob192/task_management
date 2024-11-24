import { createRouter, createWebHistory } from "vue-router";

// Import Routers
import AuthRouter from "./AuthRouter";
import SidebarRouter from "./SidebarRouter";

// Define Routes
const routes = [...AuthRouter, ...SidebarRouter];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  linkExactActiveClass: "exact-active",
});

// Check the conditions before going through any route
router.beforeEach((to, from, next) => {
  // Check if the user is trying to access a protected route without being logged in
  const isAuthenticated = localStorage.getItem("access_token");

  // If the user is not authenticated and is trying to access a route other than Login or Register
  if (!isAuthenticated && to.name !== "Login" && to.name !== "Register" && to.name !== "GoogleLogin") {
    return next({ name: "Login" }); // Redirect to Login
  }

  // Allow navigation to the target route
  next();
});

export default router;
