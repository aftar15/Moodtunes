<template>
  <div>
    <Header />
    <Sidebar />
    <SearchBar />
    <Thumbnails />
    <MusicGrid />
  </div>
</template>

<script>
  import Header from '../components/Header.vue'
  import Sidebar from '../components/Sidebar.vue'
  import SearchBar from '../components/SearchBar.vue'
  import Thumbnails from '../components/Thumbnails.vue'
  import MusicGrid from '../components/MusicGrid.vue'

  export default {
    components: {
      Header,
      Sidebar,
      SearchBar,
      Thumbnails,
      MusicGrid,
    },
    methods: {
      goToMusicPlayer() {
        this.$router.push({ name: 'MusicPlayer', query: { source: 'Home' } });
      }
    }
  }
</script>