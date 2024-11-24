import { defineStore } from "pinia";
import Auth from "../services/api/AuthApi";
import { ref } from "vue";
import Api from "../services/api/Api";

export const useAuthStore = defineStore("auth", () => {
  // State
  const accessToken = ref(localStorage.getItem("access_token") || null);
  const refreshToken = ref(localStorage.getItem("refresh_token") || null);
  const userRole = ref(localStorage.getItem("user_role") || null);
  const userInfo = ref(localStorage.getItem("user_info") ? JSON.parse(localStorage.getItem("user_info")) : null);
  const loginResponse = ref(null);
  const loggedIn = ref(!!accessToken.value);
  const errorMessage = ref(""); // Single error message
  const errorData = ref({}); // Detailed error data

  // Actions
  const register = async (data) => {
    try {
      const response = await Auth.register(data);
      setLoginInfo(response.data);

      return JSON.stringify(response);
    } catch (error) {
      // console.error("Registration failed:", error);
      setErrors(error, "registration");

      throw error;
    }
  };

  const login = async (data) => {
    try {
      const response = await Auth.login(data);
      setLoginInfo(response.data);

      return JSON.stringify(response);
    } catch (error) {
      // console.error("Login failed:", error);
      setErrors(error, "login");
      throw error;
    }
  };

  const loginWithGoogle = async () => {
    try {
      return await Auth.loginWithGoogle();
    } catch (error) {
      // console.error("Login failed:", error);
      setErrors(error, "loginWithGoogle");
      throw error;
    }
  };

  const handleGoogleCallback = async (data) => {
    try {
      // return await Auth.handleGoogleCallback(data);
      const response = await Auth.handleGoogleCallback(data);

      setLoginInfo(response.data);
      return response;
    } catch (error) {
      // console.error("Login failed:", error);
      setErrors(error, "loginWithGoogle");
      throw error;
    }
  };

  const logout = async () => {
    try {
      const response = await Auth.logout();

      return JSON.stringify(response);
    } catch (error) {
      // console.error("Login failed:", error);
      setErrors(error, "logout");
      throw error;
    }
  };

  const setLoginInfo = (data) => {
    const user = data.data;
    const token = data.data.access_token;
    const refresh = data.data.refresh_token;
    const role = data.data.roles?.[0]?.name;

    // Set state
    accessToken.value = token;
    refreshToken.value = refresh;
    userRole.value = role;
    userInfo.value = user;
    loginResponse.value = JSON.stringify(data);
    loggedIn.value = true;

    // Store in localStorage
    localStorage.setItem("access_token", token);
    localStorage.setItem("refresh_token", refresh);
    localStorage.setItem("user_role", role);
    localStorage.setItem("user_info", JSON.stringify(user));

    // Clear error data on successful login
    errorMessage.value = "";
    errorData.value = {};

    // Update the Token in Api After Login
    Api.defaults.headers["Authorization"] = "Bearer " + token;
  };

  const setErrors = (error, action) => {
    // Capture error details
    errorData.value = error.response?.data.errors || {};

    // Single error message
    errorMessage.value =
      (error.status && error.status !== 422 && error.response?.data?.message) ||
      error.response?.data?.message ||
      error.message ||
      "An error occurred during " + action;
  };

  const attempt = (token) => {
    if (token) {
      setToken(token);
    }
    if (!accessToken.value) return;
  };

  const setToken = (token) => {
    accessToken.value = token;
    loggedIn.value = true;
    localStorage.setItem("access_token", token);

    // Retrieve user info and role from localStorage if needed
    userInfo.value = JSON.parse(localStorage.getItem("user_info"));
    userRole.value = localStorage.getItem("user_role");
  };

  return {
    accessToken,
    refreshToken,
    userRole,
    userInfo,
    loginResponse,
    loggedIn,
    errorMessage,
    errorData,
    register,
    login,
    loginWithGoogle,
    handleGoogleCallback,
    logout,
    attempt,
    setLoginInfo,
    setToken,
  };
});
