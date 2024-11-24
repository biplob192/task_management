import axios from "axios";
import router from "../../router";

const baseURL = "http://127.0.0.1:8000/api/";
// const baseURL = "http://192.168.1.184:8000/api/";

const Api = axios.create({
  baseURL: baseURL,
  headers: {
    Accept: "application/json",
    Authorization: "Bearer " + localStorage.getItem("access_token"),
  },
});

// Api.interceptors.request.use((config) => {
//   const token = localStorage.getItem("access_token");
//   if (token) {
//     config.headers["Authorization"] = "Bearer " + token;
//   }
//   return config;
// });

export function http() {
  return axios.create({
    baseURL: baseURL,
    headers: {
      Accept: "application/json",
      Authorization: "Bearer " + localStorage.getItem("access_token"),
    },
  });
}

export function httpFile() {
  return axios.create({
    baseURL: baseURL,
    method: "GET",
    responseType: "blob",
    headers: {
      Accept: "application/json",
      "Content-Type": ["multipart/form-data", "application/pdf"],
      Authorization: "Bearer " + localStorage.getItem("access_token"),
    },
  });
}

// Helper function to refresh the token
async function refreshToken() {
  // console.log("Inside refreshToken");
  try {
    const response = await axios.post(`${baseURL}refresh`, null, {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("refresh_token"),
      },
    });

    const user = response.data.data;
    const role = response.data.data.roles?.[0]?.name;
    const newAccessToken = response.data.data.access_token;
    const newRefreshToken = response.data.data.refresh_token;

    if (newAccessToken) {
      localStorage.clear();
      localStorage.setItem("access_token", newAccessToken);
      localStorage.setItem("refresh_token", newRefreshToken);
      localStorage.setItem("user_role", role);
      localStorage.setItem("user_info", JSON.stringify(user));
      Api.defaults.headers["Authorization"] = "Bearer " + newAccessToken;
    }

    return newAccessToken;
  } catch (error) {
    // If refresh fails, clear tokens and redirect to login
    // localStorage.removeItem("access_token");
    localStorage.clear();
    router.push({ name: "Login" });
    throw error;
  }
}

// Axios response interceptor to handle 401 errors and token refreshing
Api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;
    const errorMessage = error.response.data.message;

    // Check if error is 401 and hasn't already been retried
    if (error.response && error.response.status === 401 && !originalRequest._retry && errorMessage != "Invalid email or password.") {
      originalRequest._retry = true; // Mark request as retry to avoid loops

      const newAccessToken = await refreshToken();

      if (newAccessToken) {
        originalRequest.headers["Authorization"] = "Bearer " + newAccessToken;
        return Api(originalRequest); // Retry the request with new token
      }
    }

    // If error isn't recoverable, throw it
    return Promise.reject(error);
  }
);

export default Api;
