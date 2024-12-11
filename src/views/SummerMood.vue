<template>
  <div>
    <Header />
    <div class="trending-music">
      <div class="music-grid">
        <div v-for="(song, index) in summersongs" :key="index" class="song-item" @click="goToMusicPlayer(song)">
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
        summersongs: [
          { cover: 'images/iaintworried.jpg', title: 'I Aint Worrried', artist: 'OneRepublic', audio: 'audio/iaintworried.mp3' },
          { cover: 'images/sunroof.jpg', title: 'Sunroof', artist: 'Nicky Youre, dazy', audio: 'audio/sunroof.mp3' },
          { cover: 'images/belongtogether.jpg', title: 'Belong Together', artist: 'Mark Ambor', audio: 'audio/belongtogether.mp3' },
          { cover: 'images/californiagurls.jpg', title: 'California Gurls', artist: 'Katy Perry ft. Snoop Dogg', audio: 'audio/californiagurls.mp3' },
          { cover: 'images/givemeeverything.jpg', title: 'Give Me Everything', artist: 'Pitbull ft. Ne-Yo, Afrojack, Nayer', audio: 'audio/givemeeverything.mp3' },
          { cover: 'images/lushlife.jpg', title: 'Lushlife', artist: 'Zara Larsson', audio: 'audio/lushlife.mp3' },
          { cover: 'images/lightitup.jpg', title: 'Light It Up', artist: 'Major Lazer', audio: 'audio/lightitup.mp3' },
          { cover: 'images/ratherbe.jpg', title: 'Rather Be', artist: 'Clean Bandit ft Jess Glynne', audio: 'audio/ratherbe.mp3' },
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
            playlist: JSON.stringify(this.summersongs),
            currentIndex: this.summersongs.findIndex(s => s.title === song.title)
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