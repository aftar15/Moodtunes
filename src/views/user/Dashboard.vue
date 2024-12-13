<template>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">
        <img src="@/assets/images/em.png" alt="MoodTunes Logo">
      </div>
      <nav>
        <router-link to="/user/dashboard" class="nav-item" active-class="active">
          <i class="fas fa-home"></i>
          Dashboard
        </router-link>
        <router-link to="/user/playlist" class="nav-item" active-class="active">
          <i class="fas fa-list"></i>
          My Playlists
        </router-link>
        <router-link to="/user/favorites" class="nav-item" active-class="active">
          <i class="fas fa-heart"></i>
          Favorites
        </router-link>
      </nav>
      <div class="user-info">
        <img :src="userAvatar" alt="User Avatar" class="avatar">
        <span>{{ userName }}</span>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <header>
        <h1>Welcome, {{ userName }}!</h1>
        <button @click="logout" class="logout-btn">Logout</button>
      </header>
      
      <transition name="fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserDashboard',
  data() {
    return {
      userName: 'User',
      userAvatar: '', // Default avatar or from user data
    }
  },
  methods: {
    logout() {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      this.$router.push('/login');
    }
  },
  mounted() {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    this.userName = user.name || 'User';
  }
}
</script>

<style scoped>
.dashboard-container {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 250px;
  background: #1a1a1a;
  color: white;
  padding: 20px;
  display: flex;
  flex-direction: column;
}

.logo {
  text-align: center;
  margin-bottom: 30px;
}

.logo img {
  width: 80px;
  height: 80px;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px;
  color: #fff;
  text-decoration: none;
  margin-bottom: 10px;
  border-radius: 8px;
  transition: background-color 0.2s;
}

.nav-item i {
  margin-right: 10px;
}

.nav-item:hover, .nav-item.active {
  background: rgba(255, 255, 255, 0.1);
}

.user-info {
  margin-top: auto;
  padding-top: 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.main-content {
  flex: 1;
  padding: 20px;
  background: #f5f5f5;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.logout-btn {
  padding: 8px 16px;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
