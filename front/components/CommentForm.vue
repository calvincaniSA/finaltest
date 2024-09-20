<template>
  <v-form @submit.prevent="submitComment">
    <v-textarea v-model="content" label="Your Comment" rows="3"></v-textarea>
    <v-btn class="mb-2 ml-2" color="primary" @click="submitComment">Submit Comment</v-btn>
  </v-form>
</template>

<script setup lang="ts">
import { ref } from "vue";
import type { BlogEntry, Comment } from "~/types/blog";

// axios
const { $axios } = useNuxtApp();

// emits
const emit = defineEmits<{
  (event: "cancel"): void;
  (event: "save", comment: Comment): void;
}>();

// data
const content = ref<string>("");

// props
const props = defineProps<{
  entry: BlogEntry;
  onSave: (comment: Comment) => void;
}>();

// functions
const submitComment = async () => {
  if (!props.entry) {
    console.error("Blog entry is not provided");
    return;
  }
  try {
    const response = await $axios.post("/comments", {
      blog_entry_id: props.entry.id,
      content: content.value,
    });
    emit("save", response.data);
    content.value = "";
  } catch (error) {
    console.error("Error submitting comment:", error);
  }
};
</script>
