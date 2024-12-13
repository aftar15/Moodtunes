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
import Dashboard from '@/views/Admin/Dashboard.vue';
import UserLogs from '@/views/Admin/UserLogs.vue';
import MusicManagement from '@/views/Admin/MusicManagement.vue';
import FavoriteManagement from '@/views/Admin/FavoriteManagement.vue';
import PlaylistManagement from '@/views/Admin/PlaylistManagement.vue';
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
  { path: '/register', redirect: '/signup' },
  { path: '/forgot-password', name: 'ForgotPassword', component: () => import('./views/ForgotPassword.vue') },
  
  // User routes
  {
    path: '/user',
    component: () => import('@/views/user/Dashboard.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: '/user/dashboard'
      },
      {
        path: 'dashboard',
        name: 'UserDashboard',
        component: () => import('@/views/user/DashboardContent.vue')
      },
      {
        path: 'playlist',
        name: 'UserPlaylist',
        component: () => import('@/views/user/PlaylistContent.vue')
      },
      {
        path: 'favorites',
        name: 'UserFavorites',
        component: () => import('@/views/user/FavoritesContent.vue')
      }
    ]
  },
  
  // Admin routes
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: '',
        redirect: '/admin/dashboard'
      },
      {
        path: 'dashboard',
        name: 'AdminDashboard',
        component: () => import('@/views/Admin/Dashboard.vue')
      },
      {
        path: 'user-logs',
        name: 'UserLogs',
        component: () => import('@/views/Admin/UserLogs.vue')
      },
      {
        path: 'music-management',
        name: 'MusicManagement',
        component: () => import('@/views/Admin/MusicManagement.vue')
      },
      {
        path: 'favorite-management',
        name: 'FavoriteManagement',
        component: () => import('@/views/Admin/FavoriteManagement.vue')
      },
      {
        path: 'playlist-management',
        name: 'PlaylistManagement',
        component: () => import('@/views/Admin/PlaylistManagement.vue')
      }
    ]
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);

  if (requiresAuth && !token) {
    next('/login');
  } else if (requiresAdmin && (!token || !user.is_admin)) {
    next('/login');
  } else {
    next();
  }
});

export default router;