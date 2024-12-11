<template>
  <div>
    <Header />
    <div class="trending-music">
      <div class="music-grid">
        <div v-for="(song, index) in funsongs" :key="index" class="song-item" @click="goToMusicPlayer(song)">
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
        funsongs: [
          { cover: 'images/partyintheusa.jpg', title: 'Party in the USA', artist: 'Miley Cyrus', audio: 'audio/partyinusa.mp3' },
          { cover: 'images/starships.jpg', title: 'Starships', artist: 'Nicki Minaj', audio: 'audio/starships.mp3' },
          { cover: 'images/djgotusfallinginloveagain.jpg', title: 'DJ Got us Fallin Inlove', artist: 'Usher ft. Pitbull', audio: 'audio/djgotusfallininlove.mp3' },
          { cover: 'images/timber.jpg', title: 'Timber', artist: 'Pitbull ft. Ke$ha', audio: 'audio/timber.mp3' },
          { cover: 'images/dieyoung.jpg', title: 'Die Young', artist: 'Ke$ha', audio: 'audio/dieyoung.mp3' },
          { cover: 'images/dontstopthemusic.jpg', title: 'Dont Stop the Music', artist: 'Rihanna', audio: 'audio/dontstopthemusic.mp3' },
          { cover: 'images/lastfridaynight.jpg', title: 'Last Friday Night', artist: 'Katy Perry', audio: 'audio/lastfridaynight.mp3' },
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
            playlist: JSON.stringify(this.funsongs),
            currentIndex: this.funsongs.findIndex(s => s.title === song.title)
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