<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" md="6">
        <v-card>
          <v-card-title class="text-h5">Register</v-card-title>
          <v-card-text>
            <v-form v-model="valid" ref="form">
              <v-text-field v-model="name" label="Name" :rules="[rules.required]" outlined required></v-text-field>
              <v-text-field
                v-model="email"
                label="Email"
                :rules="[rules.required, rules.email]"
                outlined
                required
              ></v-text-field>
              <v-text-field
                v-model="password"
                label="Password"
                :rules="[rules.required, rules.min(6)]"
                type="password"
                outlined
                required
              ></v-text-field>
              <v-text-field
                v-model="password_confirmation"
                label="Confirm Password"
                :rules="[rules.required, confirmPassword]"
                type="password"
                outlined
                required
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" @click="registerUser">Register</v-btn>
            <v-btn @click="goLogin" variant="text" class="ma-2"> Login </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
const { $axios } = useNuxtApp();

// Form validation rules
const valid = ref(false);
const name = ref("");
const email = ref("");
const password = ref("");
const password_confirmation = ref("");
const router = useRouter();

// Validation rules
const rules = {
  required: (v) => !!v || "This field is required",
  email: (v) => /.+@.+\..+/.test(v) || "Invalid email",
  min: (length) => (v) => (v && v.length >= length) || `Min ${length} characters`,
};

const confirmPassword = (v) => v === password.value || "Passwords do not match";

// functions
useHead({
  title: "Register",
  meta: [{ name: "description", content: "Register for a free account" }],
  bodyAttrs: {
    class: "register",
  },
  script: [{}],
});

const registerUser = async () => {
  try {
    const response = await $axios.post("/register", {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });
    console.log("User registered successfully:", response.data);
    router.push("/login");
  } catch (error) {
    console.error("Error registering user:", error.response.data);
  }
};

const goLogin = () => {
  router.push("/login");
};
</script>
