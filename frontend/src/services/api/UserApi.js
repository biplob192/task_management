import Api, { http, httpFile } from "./Api";
const END_POINT = "users";

export default {
  all(data) {
    // return Api.get(END_POINT);
    return Api.get(`${END_POINT}?${data}`);
    // return http().get(`${END_POINT}?${data}`);
  },

  store(data) {
    return Api.post(END_POINT, data);
    // return http().post(END_POINT, data);
  },

  show(id) {
    return http().get(`${END_POINT}/${id}`);
  },

  update(id, data) {
    return http().post(`${END_POINT}/${id}`, data);
  },

  delete(id) {
    return Api.delete(`${END_POINT}/${id}`);
    // return http().delete(`${END_POINT}/${id}`);
  },

  export() {
    return httpFile().get(`export/${END_POINT}`);
  },

  info() {
    return http().get(`${END_POINT}/current/info`);
  },

  getData(params) {
    return http().get(`${END_POINT}/get/data?${params}`);
  },
};
