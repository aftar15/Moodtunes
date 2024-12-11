<template>
    <div class="music">
      <h1>Trending Music</h1>
      <div class="music-grid">
        <MusicCard 
          v-for="(song, index) in songs" 
          :key="index"
          :song="song" 
          @playSong="playSong" 
        />
      </div>
    </div>
</template>
  
<script>
  import MusicCard from './MusicCard.vue'
  
  export default {
    components: { MusicCard },
    data() {
      return {
        songs: [
          { cover: 'images/pleasepleaseplease.jpg', title: 'Please Please Please', artist: 'Sabrina Carpenter', audio: 'audio/pleasepleaseplease.mp3' },
          { cover: 'images/diewithasmile.jpg', title: 'Die With a Smile', artist: 'Lady Gaga, Bruno Mars', audio: 'audio/diewithasmile.mp3'},
          { cover: 'images/mantra.jpg', title: 'Mantra', artist: 'JENNIE', audio: 'audio/mantra.mp3'},
          { cover: 'images/birdsofafeather.jpg', title: 'Birds of a Feather', artist: 'Billie Eilish', audio: 'audio/birdsofafeather.mp3'},
          { cover: 'images/apt.jpg', title: 'APT', artist: 'ROSÉ & Bruno Mars', audio: 'audio/apt.mp3'},
          { cover: 'images/beautifulthings.jpg', title: 'Beautiful Things', artist: 'Benson Boone', audio: 'audio/beautifulthings.mp3'},
          { cover: 'images/stargazing.jpg', title: 'Stargazing', artist: 'Myle Smith', audio: 'audio/stargazing.mp3'},
          { cover: 'images/art.jpg', title: 'ART', artist: 'Tyla', audio: 'audio/art.mp3'},
          { cover: 'images/breakingnews.jpg', title: 'Breaking News', artist: '​Flowerovlove', audio: 'audio/breakingnews.mp3'},
          { cover: 'images/taste.jpg', title: 'Taste', artist: '​Sabrina Carpenter', audio: 'audio/taste.mp3'},
        ],
      }
    },
    methods: {
      playSong(song, index) {
        this.$router.push({
          path: '/music-player',
          query: {
            cover: song.cover,
            title: song.title,
            artist: song.artist,
            audio: song.audio,
            playlist: JSON.stringify(this.songs),
            currentIndex: index,
          },
        });
      },
    },
};
</script>
  
<style scoped>
  .music {
    background-color: #f5f5f5;
    border-radius: 20px;
    padding: 20px;
    color: #000;
    max-width: 1000px;
    margin: 10px auto;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  .music h1 {
    font-size: 1.5rem;
  margin-bottom: 20px;
  transition: color 0.3s ease; /* Smooth transition for other properties */
    animation: colorChange 7s linear infinite; /* Add the animation */
}

/* Keyframes for color animation */
@keyframes colorChange {
  0% { color: #000000; }   /* Color 1: black */
  14% { color: #ff5733; }  /* Color 2: a warm red */
  28% { color: #ffc300; }  /* Color 3: a bright yellow */
  42% { color: #1f8ef1; }  /* Color 4: a calm blue */
  57% { color: #28a745; }  /* Color 5: a vibrant green */
  71% { color: #6f42c1; }  /* Color 6: a rich purple */
  85% { color: #e83e8c; }  /* Color 7: a lovely pink */
  100% { color: #000000; } /* Loop back to black */
}
  .music-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    text-decoration: none;
  }
</style>