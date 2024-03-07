import { createRouter, createWebHistory } from 'vue-router'
import TaskVue from '../Task.vue';
import SearchVue from '../Search.vue';
import TaskForm from '../forms/TaskForm.vue';
const routes = [
  {
    path: '/',
    name: 'Task',
    component: TaskVue,
  },
  {
    path: '/search',
    name: 'Search',
    component: SearchVue,
  },
  {
    path: '/task',
    name: 'TaskForm',
    component: TaskForm,
    props: true,
  },
  // 以下のようなものを追加したいどうしたらいい
  {
    path: '/task/:id/edit',
    name: 'EditTask',
    component: TaskForm,
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
