<template>
  <div>
    <Header />
    <div class="trending-music">
      <div class="music-grid">
        <div v-for="(song, index) in happysongs" :key="index" class="song-item" @click="goToMusicPlayer(song)">
          <img :src="song.cover" :alt="song.title + ' Cover'" class="cover-img">
          <div class="song-info">
            <h3>{{ song.title }}</h3>
            <p>{{ song.artist }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Header from "../components/Header.vue";

  export default {
    components: {
      Header
    },
    data() {
      return {
        happysongs: [
          { cover: 'images/happy.jpg', title: 'Happy', artist: 'Pharrell Williams', audio: 'audio/happy.mp3' },
          { cover: 'images/cantstopthefeeling.jpg', title: 'Cant Stop The Feeling', artist: 'Justin Timberlake', audio: 'audio/cantstopthefeeling.mp3' },
          { cover: 'images/walking on sunshine.jpg', title: 'Walking on Sunshine', artist: 'Katrina and the Waves', audio: 'audio/walking on sunshine.mp3' },
          { cover: 'images/levitating.jpg', title: 'Levitating', artist: 'Dua Lipa', audio: 'audio/levitating.mp3' },
          { cover: 'images/uptownfunk.jpg', title: 'UpTown Funk', artist: 'Bruno Mars', audio: 'audio/uptownfunk.mp3' },
          { cover: 'images/goodashell.png', title: 'Good as Hell', artist: 'Lizzo', audio: 'audio/goodashell.mp3' },
        ]
      };
    },
    methods: {
      goToMusicPlayer(song) {
        this.$router.push({
          name: 'MusicPlayer',
          query: {
            title: song.title,
            artist: song.artist,
            cover: song.cover,
            audio: song.audio,
            playlist: JSON.stringify(this.happysongs),
            currentIndex: this.happysongs.findIndex(s => s.title === song.title)
          }
        });
      }
    }
  };
</script>

<style scoped>
  .trending-music {
    padding: 20px;
    border-radius: 8px;
    margin: 20px 80px;
  }
  .music-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
  }
  .song-item {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    text-align: center;
    transition: transform 0.3s ease;
    cursor: pointer;
    min-width: 180px;
  }
  .song-item:hover {
    transform: scale(1.05);
  }
  .cover-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }
  .song-info {
    padding: 15px;
  }
  .song-info h3 {
    font-size: 1rem;
    font-weight: bold;
    color: #333;
    margin: 5px 0;
  }
  .song-info p {
    font-size: 0.9rem;
    color: #777;
    margin: 5px 0;
  }
</style>