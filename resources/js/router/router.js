import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import("../Pages/Index.vue")
    },
    {
        path: "/edit/:id",
        component: () => import("../Pages/Edit.vue"),
    },
    {
        path: "/new",
        component: () => import("../Pages/New.vue"),
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
