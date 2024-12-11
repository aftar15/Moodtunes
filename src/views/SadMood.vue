<template>
  <div>
    <Header />
    <div class="trending-music">
      <div class="music-grid">
        <div v-for="(song, index) in sadsongs" :key="index" class="song-item" @click="goToMusicPlayer(song)">
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
        sadsongs: [
          { cover: 'images/someoneyouloved.jpg', title: 'Someone you Loved', artist: 'Lewis Capaldi', audio: 'audio/someoneyouloved.mp3' },
          { cover: 'images/happierthanever.jpg', title: 'Happier than Ever', artist: 'Billie Eilish', audio: 'audio/happierthanever.mp3' },
          { cover: 'images/ihateyouiloveyou.jpg', title: 'I hate you I love you', artist: 'Gnash ft. Olivia O Brien', audio: 'audio/ihateyouiloveyou.mp3' },
          { cover: 'images/beforeyougo.jpg', title: 'Before You Go', artist: 'Lewis Capaldi', audio: 'audio/beforeyougo.mp3' },
          { cover: 'images/onlylovecanhurtlikethis.jpg', title: 'Only Love Can Hurt Like This', artist: 'Paloma Faith', audio: 'audio/onlylovecanhurtlikethis.mp3' },
          { cover: 'images/athousandyears.jpg', title: 'A Thousand Years', artist: 'Christina Perri', audio: 'audio/athousandyears.mp3' },
          { cover: 'images/inthestars.jpg', title: 'In the Stars', artist: 'Benson Boone', audio: 'audio/inthestars.mp3' },
          { cover: 'images/invisible.jpg', title: 'Invisible', artist: 'Hunter Hayes', audio: 'audio/invisible.mp3' },
          { cover: 'images/dancingwithyourghost.jpg', title: 'Dancing With Your Ghost', artist: 'Sasha Sloan', audio: 'audio/dancingwithyourghost.mp3' },
          { cover: 'images/bringmeback.jpg', title: 'Bring Me Back', artist: 'Miles Away ft. Claire Ridgely', audio: 'audio/bringmeback.mp3' },
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
        playlist: JSON.stringify(this.sadsongs),
        currentIndex: this.sadsongs.findIndex(s => s.title === song.title)
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