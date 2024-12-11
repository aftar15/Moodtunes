<template>
    <div class="card" :style="{ backgroundColor: color }">
      <h3>{{ value }}</h3>
      <p>{{ label }}</p>
    </div>
</template>
  
<script>
  export default {
    props: ["value", "label", "color"],
  };
</script>
  
<style scoped>
    .card {
        width: 150px;
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 8px;
        color: #000000;
        margin: 20px 5px;
    }
</style>  