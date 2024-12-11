<template>
    <div class="search-bar">
      <input
            type="text"
            v-model="keyword"
            placeholder="Search for a keyword"
            @input="search"
      />
      <i class="icon-search"></i>
    </div>
</template>
  
<script>
  export default {
    data() {
      return {
        keyword: "",
      };
    },
    methods: {
      search() {
        this.$emit("search", this.keyword);
      },
    },
  };
</script>
  
<style scoped>
    .search-bar {
        display: flex;
        align-items: center;
        margin: 20px 0;
        background: #f1f1f1;
        padding: 10px;
        border-radius: 5px;
    }
    input {
        border: none;
        flex: 1;
        background: none;
        outline: none;
    }
    .icon-search {
        color: #777;
        font-size: 20px;
    }
</style>  