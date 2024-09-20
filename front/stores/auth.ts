import { defineStore } from 'pinia';
import { ref } from 'vue';
import { useNuxtApp } from '#app';

export const useAuthStore = defineStore('auth', () => {
  const isAuthenticated = ref(false);
  const nuxtApp = useNuxtApp();
  const $axios = nuxtApp.$axios;
  const isClient = typeof window !== 'undefined';

  const login = () => {
    isAuthenticated.value = true;
  };

  const logout = () => {
    isAuthenticated.value = false;
    document.cookie = 'token=; Max-Age=0';
  };

  const checkAuth = async () => {
    if (!isClient) return;
    try {
      const token = document.cookie.split('; ').find(row => row.startsWith('token='));
      if (token) {
        const response = await $axios.get('/user');
        if (response.status === 200) {
          login();
        } else {
          logout();
        }
      } else {
        logout();
      }
    } catch (error) {
      logout();
    }
  };

  return {
    isAuthenticated,
    login,
    logout,
    checkAuth,
  };
});
