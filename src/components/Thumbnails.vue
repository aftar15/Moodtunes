<template>
  <div class="thumbnails">
    <p class="thumbnail-emoji">😢</p>
    <p class="thumbnail-emoji">💪</p>
    <p class="thumbnail-emoji">😊</p>
    <p class="thumbnail-emoji">🎉</p>
    <p class="thumbnail-emoji">🌞</p>
  </div>
</template>

<script>
  export default {};
</script>
  
<style scoped>
   .thumbnails {
    display: flex;
    gap: 20px;
    justify-content: center;
    padding: 7px;
    margin-bottom: 5px;
  }

  .thumbnail-emoji {
    font-size: 5.5rem; /* Adjust size as desired */
    cursor: pointer;
    transition: transform 0.2s; /* Smooth transition for lift effect */
  }

  .thumbnail-emoji:hover {
    transform: translateY(-10px); /* Creates the lift effect */
  }
</style>
