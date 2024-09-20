import axios from 'axios'
import { defineNuxtPlugin } from '#app'

export default defineNuxtPlugin(nuxtApp => {
  const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true,
  });
  api.interceptors.request.use((config) => {
    const token = document.cookie.split('; ').find(row => row.startsWith('token='));
    if (token) {
      config.headers.Authorization = `Bearer ${token.split('=')[1]}`;
    }
    return config;
  }, (error) => {
    return Promise.reject(error);
  });

  nuxtApp.provide('axios', api);
});
