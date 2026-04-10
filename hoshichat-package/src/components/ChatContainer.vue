<template>
  <div class="hoshi-chat-container">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'ChatContainer',
};
</script>

<style scoped>
.hoshi-chat-container {
  display: flex;
  width: 100%;
  height: 100vh;
  overflow: hidden;
  background: #f5f5f5;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
    Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}
</style>
