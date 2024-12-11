<template>
  <div class="sidebar">
    <div class="logo">
        <img src="@/assets/images/em.png" alt="MoodTunes Logo" class="logo-img" />
      <div class="logo-text">MoodTunes</div>
    </div>
    <nav>
      <router-link to="/dashboard" class="nav-item">Home</router-link>
      <router-link to="/userlogs" class="nav-item">User Logs</router-link>
      <router-link to="/music" class="nav-item">Music</router-link>
      <router-link to="/favorites" class="nav-item">Favorites</router-link>
      <router-link to="/playlist" class="nav-item">Playlist</router-link>
    </nav>
  </div>
</template>

<script>
  export default {};
</script>
  
<style scoped>
    .sidebar {
        background-color: #5a83db;
        height: 100vh;
        width: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 0;
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
    }
    .logo-text {
        font-size: 30px;
    }
    .nav-item {
      display: block;
      margin: 10px 0;
      text-align: left;
      font-size: 20px;
      color: #fff;
      text-decoration: none;
      transition: color 0.3s;
    }
    .nav-item:hover {
        color: #020202;
    }
</style>  