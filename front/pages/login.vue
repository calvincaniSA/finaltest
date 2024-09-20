<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="6">
        <v-card>
          <v-card-title class="text-h5">Login</v-card-title>
          <v-card-subtitle>Enter your credentials to access your account</v-card-subtitle>

          <v-form v-model="valid" ref="form">
            <v-card-text>
              <v-text-field v-model="email" label="Email" type="email" :rules="emailRules" required />
              <v-text-field v-model="password" label="Password" type="password" :rules="passwordRules" required />
            </v-card-text>

            <v-card-actions>
              <v-btn :disabled="!valid" @click="handleLogin" color="primary" class="ma-2"> Login </v-btn>
              <v-btn @click="goRegister" variant="text" class="ma-2"> Register </v-btn>
            </v-card-actions>
          </v-form>

          <v-alert v-if="error" type="error" dismissible class="ma-2">
            {{ error }}
          </v-alert>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const { $axios } = useNuxtApp();
const router = useRouter();
const authStore = useAuthStore();

// state
const valid = ref(false);

// data
const email = ref<string>("");
const password = ref("");
const error = ref<string | null>(null);

// rules
const emailRules = [
  (v: string) => !!v || "Email is required",
  (v: string) => /.+@.+\..+/.test(v) || "Email must be valid",
];

const passwordRules = [(v: string) => !!v || "Password is required"];

// functions
useHead({
  title: "Login",
  meta: [{ name: "description", content: "Log into your account" }],
  bodyAttrs: {
    class: "login",
  },
  script: [{}],
});
const handleLogin = async () => {
  try {
    const response = await $axios.post("/login", { email: email.value, password: password.value });
    if (response.status === 200 && response.data.token) {
      document.cookie = `token=${response.data.token}; path=/; max-age=${60 * 60 * 24 * 30}; SameSite=Lax`;
      authStore.login();
      router.push("/");
    }
  } catch (err) {
    error.value = "Invalid email or password";
  }
};

const goRegister = () => {
  router.push("/register");
};
</script>
