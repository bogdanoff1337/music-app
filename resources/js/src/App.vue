<template>
    <header class="header">
      <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="#">Main</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">favorite</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Likes</a>
        </li>
        <li class="nav-item">
          <a v-if="isAuthenticated" class="btn btn-outline-success">{{ userName }}</a>
          <a v-else @click="redirectToSpotify" class="btn btn-outline-success" href="#">Login with Spotify</a>
        </li>
      </ul>
    </header>
  </template>
  
  <script lang="ts">
  import { defineComponent } from 'vue';
  
  export default defineComponent({
    data() {
      return {
        isAuthenticated: false, // Змінна для визначення, чи авторизований користувач
        userName: '', // Змінна для збереження імені користувача
      };
    },
    methods: {
      redirectToSpotify() {
        // Відправити користувача на сторінку авторизації Spotify
        window.location.href = '/spotify/redirect';
      },
    },
    mounted() {
      // Перевірка, чи є ім'я користувача в сесії або базі даних
      // Якщо так, оновіть дані в компоненті
      const userNameFromSession = sessionStorage.getItem('user_name');
      this.userName = userNameFromSession ? userNameFromSession : '';
      if (userNameFromSession) {
        this.isAuthenticated = true;
        this.userName = userNameFromSession;
      }
    },
  });
  </script>
  
  <style lang="less" scoped>
  .header {
    width: 992px;
    margin: 0 auto;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  </style>
  