import { createRouter, createWebHistory } from 'vue-router'
import TaskVue from '../Task.vue';
import CategoryVue from '../Category.vue';
const routes = [
  {
    path: '/',
    name: 'Task',
    component: TaskVue
  },
  {
    path: '/category',
    name: 'Category',
    component: CategoryVue
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
