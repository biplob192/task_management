import RegisterView from "../views/auth/RegisterView.vue";
import LoginView from "../views/auth/LoginView.vue";
import GoogleCallback from "@/pages/GoogleCallback.vue";

// Reusable beforeEnter guard function
const redirectIfAuthenticated = (to, from, next) => {
  // If the user already has a token, redirect to the dashboard
  if (localStorage.getItem("access_token")) {
    return next({ name: "Dashboard" });
  }
  next(); // Allow access if there's no token
};

export default [
  {
    path: "/register",
    name: "Register",
    component: RegisterView,
    beforeEnter: redirectIfAuthenticated,
  },

  {
    path: "/login",
    name: "Login",
    component: LoginView,
    beforeEnter: redirectIfAuthenticated,
  },

  {
    path: "/auth/google/callback",
    name: "GoogleLogin",
    component: GoogleCallback,
    beforeEnter: redirectIfAuthenticated,
  },
];
