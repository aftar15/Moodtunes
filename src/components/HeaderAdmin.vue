<template>
    <header class="header">
      <h1>{{ title }}</h1>
      <div class="actions">
        <i class="icon-bell"></i>
        <router-link to="/home" class="logout">Logout</router-link>
      </div>
    </header>
  </template>  
  
<script>
  export default {
    props: ["title"],
  };
</script>
  
<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background-color: #fff;
  border-bottom: 1px solid #ccc;
}
.actions {
  display: flex;
  align-items: center;
  gap: 20px;
}
.icon-bell {
  font-size: 18px;
  color:#000;
}
.logout {
    font-size: 1rem;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    border: 0.5px solid #000;
    box-shadow: 0 5px 8px rgba(0, 0, 0, 0.1);
    margin-left: auto;
    text-decoration: none;
    color: #000;
}
.logout:hover{
    transform: scale(1.1);
}
</style>
