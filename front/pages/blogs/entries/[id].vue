<template>
  <div id="blogEntryPage" class="page">
    <v-container class="d-flex justify-center" fluid>
      <v-sheet elevation="0" class="p-4 w-2/4">
        <v-card>
          <v-card-title>{{ blogEntry.title }}</v-card-title>
          <v-card-text>{{ blogEntry.content }}</v-card-text>
          <v-divider></v-divider>
          <v-card-subtitle class="mt-4">Comments</v-card-subtitle>
          <v-list>
            <v-list-item v-for="comment in blogEntry.comments" :key="comment.id">
              <v-list-item-title>{{ comment.user.name }}</v-list-item-title>
              <v-list-item-subtitle>{{ comment.content }}</v-list-item-subtitle>
            </v-list-item>
          </v-list>
          <CommentForm :entry="blogEntry" :onSave="handleCommentSave" />
          <v-btn class="mt-4 ml-2 mb-2" @click="goBack">Back</v-btn>
        </v-card>
      </v-sheet>
    </v-container>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useNuxtApp } from "#app";
import type { BlogEntry, Comment } from "~/types/blog";
import CommentForm from "~/components/CommentForm.vue";

const route = useRoute();
const router = useRouter();
const { $axios } = useNuxtApp();

const blogEntry = ref<BlogEntry>({
  title: "",
  content: "",
  blog_id: 0,
  comments: [],
});

const fetchBlogEntryDetails = async () => {
  const entryId = Number(route.params.id);
  try {
    const response = await $axios.get(`/blog-entries/${entryId}`);
    blogEntry.value = response.data;
  } catch (error) {
    console.error("Error fetching blog entry details:", error);
  }
};

const handleCommentSave = (comment: Comment) => {
  blogEntry.value.comments.push(comment);
};

const goBack = () => {
  router.push(`/blogs/${blogEntry.value.blog_id}`);
};

useHead({
  title: "Blog Entry",
  meta: [{ name: "description", content: "Blog Entry" }],
  bodyAttrs: {
    class: "blog-entry",
  },
  script: [{}],
});

// hooks
onMounted(() => {
  fetchBlogEntryDetails();
});
</script>
