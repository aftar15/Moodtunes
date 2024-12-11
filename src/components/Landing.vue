<template>
  <div class="landing-container">
    <div class="content">
      <div class="logo">
        <img src="@/assets/images/em.png" alt="MoodTunes Logo" class="logo-img" />
        <h1 class="title">MoodTunes</h1>
      </div>
      <h2 class="tagline">Track your mood,<br>Tune your day</h2>
      <router-link to="/home" class="cta-button">
        <span>&#10132;</span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "Landing",
};
</script>

<style scoped>
.landing-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: #cdeaff;
}
.content {
  text-align: center;
  background-color: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
  width: 80%;
  max-width: 600px;
}
.logo {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
}
.logo-img {
  width: 100px;
  height: 100px;
  cursor: pointer;
}
.title {
  color: #68a4ff;
  font-weight: bold;
}
.tagline {
  font-size: 2rem;
  margin-top: 20px;
  color: #333;
}
.cta-button {
  display: inline-block;
  margin-top: 30px;
  padding: 15px;
  background-color: #4a4a4a;
  color: white;
  border: none;
  border-radius: 50%;
  font-size: 1.5rem;
  text-decoration: none;
  transition: background-color 0.3s;
}
.cta-button:hover {
  background-color: #333;
  text-decoration: none;
}
</style>