<template>
  <div class="p-5 w-2/4 m-auto">
    <v-row class="justify-between">
      <v-btn variant="text" to="/">Home</v-btn>
      <v-btn variant="text" to="/about">About</v-btn>
      <v-btn variant="text" to="/blogs">Blogs</v-btn>
      <v-btn v-if="!store.isAuthenticated" variant="text" to="/register">Register</v-btn>
      <v-btn v-if="!store.isAuthenticated" variant="text" to="/login">Login</v-btn>
      <v-btn v-if="store.isAuthenticated" variant="text" @click="handleLogout">Logout</v-btn>
    </v-row>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

// router
const router = useRouter();

// store
const store = useAuthStore();

// functions
const handleLogout = async () => {
  try {
    store.logout();
    router.push("/login");
  } catch (error) {
    console.error("Logout failed:", error);
  }
};
</script>
