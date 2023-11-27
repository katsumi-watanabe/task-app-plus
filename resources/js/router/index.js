import { createRouter, createWebHistory } from 'vue-router'
import TaskVue from '../Task.vue';
import SearchVue from '../Search.vue';
const routes = [
  {
    path: '/',
    name: 'Task',
    component: TaskVue
  },
  {
    path: '/search',
    name: 'Search',
    component: SearchVue
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
