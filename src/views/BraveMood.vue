<template>
  <div>
    <Header />
    <div class="trending-music">
      <div class="music-grid">
        <div v-for="(song, index) in motivationalsongs" :key="index" class="song-item" @click="goToMusicPlayer(song)">
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
        motivationalsongs: [
          { cover: 'images/fightsong.jpg', title: 'Fight Song', artist: 'Rachel Platten', audio: 'audio/fightsong.mp3' },
          { cover: 'images/lovemyself.jpg', title: 'Love Myself', artist: 'Hailee Steinfeld', audio: 'audio/lovemyself.mp3' },
          { cover: 'images/halloffame.jpg', title: 'Hall Of Fame', artist: 'The Script', audio: 'audio/halloffame.mp3' },
          { cover: 'images/bestofme.jpg', title: 'Best of Me', artist: 'Neffex', audio: 'audio/bestofme.mp3' },
          { cover: 'images/scrastoyourbeautiful.jpg', title: 'Scars To Your Beautiful', artist: 'Alessia Cara', audio: 'audio/scarstoyourbeautiful.mp3' },
          { cover: 'images/stronger.jpg', title: 'Stronger', artist: 'Kelly Clarkson', audio: 'audio/stronger.mp3' },
          { cover: 'images/askyfllofstars.jpg', title: 'A Sky Full Of Stars', artist: 'Coldplay', audio: 'audio/askyfullofstars.mp3' },
          { cover: 'images/riseup.jpg', title: 'Rise Up', artist: 'Andra Day', audio: 'audio/riseup.mp3' },
          { cover: 'images/brave.jpg', title: 'Brave', artist: 'Sara Bareilles', audio: 'audio/brave.mp3' },
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
            playlist: JSON.stringify(this.motivationalsongs),
            currentIndex: this.motivationalsongs.findIndex(s => s.title === song.title)
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