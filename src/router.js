import { createRouter, createWebHistory } from 'vue-router';
import Landing from './components/Landing.vue';
import Home from './views/Home.vue';
import MusicPlayer from './components/MusicPlayer.vue';
import SadMood from './views/SadMood.vue';
import BraveMood from './views/BraveMood.vue';
import HappyMood from './views/HappyMood.vue';
import FunMood from './views/FunMood.vue';
import SummerMood from './views/SummerMood.vue';
import Login from './views/login.vue';
import Signup from './views/Signup.vue';
import Dashboard from '@/views/admin/Dashboard.vue';
import UserLogs from '@/views/admin/UserLogs.vue';
import MusicManagement from '@/views/admin/MusicManagement.vue';
import FavoriteManagement from '@/views/admin/FavoriteManagement.vue';
import PlaylistManagement from '@/views/admin/PlaylistManagement.vue';
import Card from "@/components/Card.vue";
import Table from "@/components/Table.vue";
import HeaderAdmin from "@/components/HeaderAdmin.vue";

const routes = [
  { path: '/', component: Landing },
  { path: '/home', component: Home },
  {
    path: '/music-player',
    name: 'MusicPlayer',
    component: () => import('./components/MusicPlayer.vue'),
    props: (route) => ({ source: route.query.source }),
  },
  { path: '/sadmood', name: 'SadMood', component: SadMood },
  { path: '/motivationalmood', name: 'BraveMood', component: BraveMood },
  { path: '/happymood', name: 'HappyMood', component: HappyMood },
  { path: '/funmood', name: 'FunMood', component: FunMood },
  { path: '/summermood', name: 'SummerMood', component: SummerMood },
  { path: '/login', name: 'Login', component: Login },
  { path: '/signup', name: 'Signup', component: Signup },
  {
    path: '/admin/dashboardhome',
    component: () => import('@/views/admin/Dashboard.vue'),
    meta: { requiresAuth: true },
  },
  { path: '/dashboard', component: Dashboard},
  { path: '/userlogs', component: UserLogs},
  { path: '/music', component: MusicManagement},
  { path: '/favorites', component: FavoriteManagement},
  { path: '/playlist', component: PlaylistManagement}
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('adminAuth') === 'true';
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default router;