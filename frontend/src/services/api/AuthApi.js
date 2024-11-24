import Api from "./Api";

export default {
  register(data) {
    return Api.post("register", data);
  },

  login(data) {
    return Api.post("login", data);
  },

  loginWithGoogle() {
    return Api.get("auth/google");
  },

  handleGoogleCallback(data) {
    return Api.get("auth/google/callback", data);
  },

  logout() {
    return Api.post("logout");
  },
};
