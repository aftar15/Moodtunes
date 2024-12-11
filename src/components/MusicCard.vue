<template>
    <div class="card" @click="playSong(song)">
      <div class="cover">
        <img :src="song.cover" alt="Album Cover" />
      </div>
      <div class="info">
        <p class="titlee">{{ song.title }}</p>
        <p class="artist">{{ song.artist }}</p>
      </div>
    </div>
</template>
  
<script>
  export default {
    props: ['song'],
    methods: {
      playSong(song) {
        this.$emit('playSong', song);
      },
    },
  };
</script>
  
<style scoped>
  .card {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    text-align: center;
    transition: transform 0.2s ease;
    cursor: pointer;
    box-shadow: 0 8px 11px rgba(0, 0, 0, 0.1);
    text-decoration: none;
  }
  .card:hover {
    transform: scale(1.05);
    text-decoration: none;
  }
  .cover {
    width: 100%;
    height: 150px;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  .cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .info {
    padding: 10px;
  }
  .titlee {
    font-size: 1rem;
    font-weight: 600;
    color: #000;
    margin: 5px 0;
  }
  .artist {
    font-size: 0.875rem;
    color: #b3b3b3;
  }
</style>