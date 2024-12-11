<template>
  <div class="emoji-search-container">
    <div class="search-bar">
      <input
        type="text"
        v-model="searchQuery"
        @keydown.enter="performSearch"
        placeholder="🔍 Search for emoji..."
      />
    </div>
    <div class="search-message">
      <div class="search-results" v-if="searchResults.length">
        <div v-for="(item, index) in searchResults" :key="index">
          {{ item.title }} by {{ item.artist }}
        </div>
      </div>
      <div v-else-if="!isEmoji(searchQuery) && searchQuery">
        Emoji search only.
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        searchQuery: '',
        searchResults: [],
        emojiToMusic: {
          "😢": [],
          "💪": [],
          "😊": [],
          "🎉": [],
          "🌞": [],
        },
      };
    },
    methods: {
      performSearch() {
        this.searchResults = [];
        
        const trimmedQuery = this.searchQuery.trim();

        if (trimmedQuery && this.isEmoji(trimmedQuery)) {
          const musicResults = this.emojiToMusic[trimmedQuery];
          if (musicResults) {
            this.searchResults = musicResults;
          } else {
            this.searchResults = []; // No results found
          }
          if (trimmedQuery === "💪") {
            this.$router.push({ name: "BraveMood" }); // Adjust the name to match your route
          }
          if (trimmedQuery === "😢") {
            this.$router.push({ name: "SadMood" }); // Adjust the name to match your route
          }
          if (trimmedQuery === "😊") {
            this.$router.push({ name: "HappyMood" }); // Adjust the name to match your route
          }
          if (trimmedQuery === "🎉") {
            this.$router.push({ name: "FunMood" }); // Adjust the name to match your route
          }
          if (trimmedQuery === "🌞") {
            this.$router.push({ name: "SummerMood" }); // Adjust the name to match your route
          }
        }
      },
      isEmoji(input) {
        return /[\u{1F300}-\u{1FAFF}]/u.test(input);
      },
    },
  };
</script>

<style scoped>
  .emoji-search-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 20px;
  }
  .search-bar {
    width: 100%;
    max-width: 600px;
  }
  .search-bar input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #0c0c0c;
    border-radius: 25px;
    background-color: #fff;
  }
  .search-message {
    width: 100%;
    max-width: 600px;
    padding: 10px;
  }
</style>