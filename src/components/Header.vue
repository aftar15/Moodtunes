<template>
  <div class="header">
    <router-link to="/">
      <img src="@/assets/images/em.png" alt="MoodTunes Logo" class="logo-img" />
    </router-link>

    <h1 v-if="isMusicPlayerPage">MusicPlayer <span class="mood">MoodTunes</span>!</h1>
    <h1 v-else-if="moodPageTitle">{{ moodPageTitle }} <span class="mood">MoodTunes</span>!</h1>
    <h1 v-else>Hi, welcome to <span class="mood">MoodTunes</span>!</h1>

    <div class="button-group" v-if="moodPageTitle">
      <router-link to="/home" class="home-button">🏠</router-link>
    </div>
    <div class="button-group" v-else-if="isMusicPlayerPage">
      <a @click.prevent="goBack" class="back-button">⬅</a>
      <router-link to="/home" class="home-button">🏠</router-link>
    </div>

    <div v-else class="menu-icon" @click="toggleSidebar">☰</div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        isMusicPlayerPage: false,
      };
    },
    computed: {
      moodPageTitle() {
      const moodTitles = {
          SadMood: 'Sad',
          BraveMood: 'Motivational',
          FunMood: 'Fun',
          HappyMood: 'Happy',
          SummerMood: 'Summer',
        };
        return moodTitles[this.$route.name] || null;
      },
    },
    watch: {
      $route(to) {
        this.isMusicPlayerPage = to.name === 'MusicPlayer';
      },
    },
    methods: {
      toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.style.width = sidebar.style.width === '250px' ? '0' : '250px';
      },
      goBack() {
        this.$router.go(-1);
      },
    },
    mounted() {
      this.isMusicPlayerPage = this.$route.name === 'MusicPlayer';
    },
  };
</script>

<style scoped>
  .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 10px 20px;
    
  }
  .logo-img {
    width: 80px;
    height: 80px;
  }
  .logo-img:hover {
    transform: scale(1.1);
  }
  .mood {
    color: #68a4ff;
    font-weight: bold;
  }
  .menu-icon {
    font-size: 1.5rem;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    border: 0.5px solid #000;
    box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
    margin-left: auto;
  }
  .menu-icon:hover {
    transform: scale(1.1);
  }
  .button-group {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-left: auto;
    margin-right: 20px;
  }
  .back-button, .home-button {
    font-size: 1.5rem;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    border: 0.5px solid #000;
    box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
    text-decoration: none;
  }
  .back-button:hover, .home-button:hover {
    transform: scale(1.1);
  }
</style>