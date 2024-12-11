<template>
  <div id="sidebar" class="sidebar">
    <button class="back-btn" @click="closeSidebar">⬅</button>
    <img src="@/assets/images/p.png" alt="Profile Image" class="sidebar-profile-img" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">


    <div>
    <router-link to="/login" class="sidebar-btn">
      <span>
        <i class="bx bx-heart"></i>
      </span>
      <span>Like</span>
    </router-link>

    <router-link to="/playlist" class="sidebar-btn">
      <span>
        <i class="bx bx-music"></i>
      </span>
      <span>Playlist</span>
    </router-link>
  </div>

    <div class="login-container">
      <router-link to="/login" class="login-btn">Login/Signup</router-link>
    </div>
  </div>
</template>

<script>
  export default {
    methods: {
      closeSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.style.width = "0";
      }
    }
  };
</script>
  
<style scoped>
  .sidebar {
  height: 100%;
  width: 0;
  position: fixed;
  justify-content: center;
  top: 0;
  right: 0;
  background-color: #fff; /* Keep this as requested */
  overflow-x: hidden;
  transition: 0.3s;
  z-index: 1;
  border-left: 1px solid rgba(70, 130, 180, 0.4); /* Light blue border */
  box-shadow: -3px 0 10px rgba(0, 0, 0, 0.15); /* Deeper shadow for a 3D effect */
}

.sidebar-profile-img {
  border-radius: 50%;
  width: 80px;
  margin-left: 89px;
  margin-top: 100px;
}
.sidebar-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: #378aff;
  font-size: 1rem;
  font-weight: 500;
  background-color: transparent;
  cursor: pointer;
  padding: 10px;
  transition: color 0.2s ease;
  border-radius: 20px;
  width: 70%;
  margin-left: 38px;
}

.back-btn {
    display: flex;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    border: 0.5px solid #000;
    box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
    margin-left: auto;
    margin: 40px 195px;
  }
  .back-btn:hover {
    transform: scale(1.1);
  }
  
.icon {
 
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 5px;
}

.sidebar-btn:hover {
  background: linear-gradient(145deg, #5ca8ff, #6ebbfe); /* 3D blue gradient */
  color: #fff; /* Text color on hover */
  box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.1), inset -1px -1px 5px rgba(255, 255, 255, 0.3); /* Inner shadows for depth */
  text-decoration: none;
}
.login-container {
  display: flex;
  justify-content: center;
  margin-top: 70%;
}
.login-btn {
  text-decoration: none;
  font-size: 1rem;
  font-weight: 500;
  color: #3b82f6; /* Softer blue text */
  padding: 10px 20px;
  border: 2px solid #3b82f6; /* Softer blue border */
  border-radius: 20px;
  background-color: #fff; /* White background */
  transition: all 0.3s ease;
  box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1), -1px -1px 5px rgba(255, 255, 255, 0.5); /* Subtle initial shadow */
}

.login-btn:hover {
  background: linear-gradient(145deg, #2d6dc3, #4ea6f8); /* Softer blue gradient */
  color: #fff; /* White text on hover */
  border: 2px solid #2d6dc3; /* Slightly deeper blue border */
  box-shadow: 2px 2px 8px rgba(45, 109, 195, 0.3), -2px -2px 8px rgba(78, 166, 248, 0.2); /* Reduced shadow intensity */
}

</style>