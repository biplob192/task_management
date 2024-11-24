// Import views
import HomeView from "../views/HomeView.vue";
import DashboardView from "@/views/DashboardView.vue";
import UsersIndexView from "@/views/users/UsersIndexView.vue";
import TaskIndexView from "@/views/task/TaskIndexView.vue";
import ListView from "@/views/ListView.vue";

export default [
  {
    path: "/home",
    redirect: "/",
  },
  {
    path: "/",
    name: "Home",
    component: HomeView,
    children: [
      {
        path: "",
        name: "Dashboard",
        component: DashboardView,
      },
      {
        path: "users",
        name: "UserIndex",
        component: UsersIndexView,
      },
      {
        path: "tasks",
        name: "Tasks",
        component: TaskIndexView,
      },
      {
        path: "list",
        name: "List",
        component: ListView,
      },
      {
        path: "settings",
        name: "Settings",
        component: () => import("../views/SettingsView.vue"),
      },
      // Add other routes here that will render inside MainContentComponent
    ],
  },
];
