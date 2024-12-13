<template>
  <div class="sidebar">
    <div class="logo">
      <img src="@/assets/images/em.png" alt="MoodTunes Logo" class="logo-img" />
      <div class="logo-text">MoodTunes</div>
    </div>
    <nav>
      <router-link to="/admin/dashboard" class="nav-item">
        <i class="fas fa-home"></i>
        Dashboard
      </router-link>
      <router-link to="/admin/user-logs" class="nav-item">
        <i class="fas fa-history"></i>
        User Logs
      </router-link>
      <router-link to="/admin/music-management" class="nav-item">
        <i class="fas fa-music"></i>
        Music Management
      </router-link>
      <router-link to="/admin/favorite-management" class="nav-item">
        <i class="fas fa-heart"></i>
        Favorite Management
      </router-link>
      <router-link to="/admin/playlist-management" class="nav-item">
        <i class="fas fa-list"></i>
        Playlist Management
      </router-link>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'SidebarAdmin'
};
</script>
  
<style scoped>
.sidebar {
  background-color: #5a83db;
  height: 100vh;
  width: 250px;
  display: flex;
  flex-direction: column;
  padding: 20px 0;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.logo {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #fff;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 40px;
}

.logo-img {
  width: 70px;
  height: auto;
  margin-bottom: 10px;
}

.logo-text {
  font-size: 24px;
}

nav {
  width: 100%;
  padding: 0 20px;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  margin: 8px 0;
  text-align: left;
  font-size: 16px;
  color: #fff;
  text-decoration: none;
  transition: all 0.3s;
  border-radius: 8px;
}

.nav-item i {
  margin-right: 12px;
  width: 20px;
  text-align: center;
}

.nav-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
  transform: translateX(5px);
}

.nav-item.router-link-active {
  background-color: rgba(255, 255, 255, 0.2);
  font-weight: bold;
}
</style>