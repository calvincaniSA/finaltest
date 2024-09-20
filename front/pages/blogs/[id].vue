<template>
  <v-container fluid>
    <v-sheet elevation="0" class="p-4 w-2/4 m-auto">
      <!-- Header Section with Title and Button on Same Line -->
      <v-row class="align-center justify-space-between mb-5">
        <v-col cols="auto">
          <h1 class="text-h4 font-weight-black mb-0">{{ blog?.title }}</h1>
        </v-col>
        <v-col cols="auto">
          <v-btn color="success" @click="openEntryDialog('create')" v-if="store.isAuthenticated"> Add New Entry </v-btn>
        </v-col>
      </v-row>

      <!-- Description Section Below Title and Button -->
      <v-row class="mb-5">
        <v-col cols="12">
          <p class="text-body-1">{{ blog?.description }}</p>
        </v-col>
      </v-row>

      <!-- Blog Entries Section -->
      <v-row>
        <v-col cols="12" v-for="entry in blogEntries" :key="entry.id">
          <v-card class="mb-3">
            <v-card-title>{{ entry.title }}</v-card-title>
            <v-card-text>{{ entry.content }}</v-card-text>
            <v-card-actions>
              <v-btn color="primary" @click="viewEntry(entry)">View Entry</v-btn>
              <v-btn color="primary" @click="editEntry(entry)" v-if="store.isAuthenticated">Edit Entry</v-btn>
              <v-btn color="error" @click="deleteEntry(entry)" v-if="store.isAuthenticated">Delete Entry</v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>

      <!-- Blog Entry Form Dialog -->
      <v-dialog v-model="showEntryDialog" max-width="600px" persistent>
        <BlogEntryForm
          :entry="currentEntry"
          :action="entryAction"
          :blogId="blog?.id ?? 0"
          @save="saveEntry"
          @cancel="closeEntryDialog"
        />
      </v-dialog>
    </v-sheet>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import BlogEntryForm from "~/components/BlogEntryForm.vue";
import type { Blog, BlogEntry } from "~/types/blog";

// axios
const { $axios } = useNuxtApp();

// router
const route = useRoute();
const router = useRouter();

// store
const store = useAuthStore();

const blog = ref<Blog | null>(null);
const blogEntries = ref<BlogEntry[]>([]);
const showEntryDialog = ref(false);
const currentEntry = ref<BlogEntry | undefined>(undefined);
const entryAction = ref<"create" | "edit">("create");

const fetchBlogDetails = async () => {
  const blogId = Number(route.params.id);
  try {
    const blogResponse = await $axios.get(`/blogs/${blogId}`);
    blog.value = blogResponse.data;

    const entriesResponse = await $axios.get(`/blogs/${blogId}/entries`);
    blogEntries.value = entriesResponse.data;
  } catch (error) {
    console.error("Error fetching blog details:", error);
  }
};

const openEntryDialog = (action: "create" | "edit", entry?: BlogEntry) => {
  entryAction.value = action;
  currentEntry.value = entry;
  showEntryDialog.value = true;
};

const closeEntryDialog = () => {
  showEntryDialog.value = false;
};

const viewEntry = (entry: BlogEntry) => {
  router.push(`/blogs/entries/${entry.id}`);
};

const editEntry = (entry: BlogEntry) => {
  openEntryDialog("edit", entry);
};

const saveEntry = async (entry: BlogEntry) => {
  try {
    if (entryAction.value === "create") {
      const response = await $axios.post(`/blog-entries`, entry);
      const createdEntry = response.data;
      blogEntries.value.push(createdEntry);
    } else {
      await $axios.put(`/blog-entries/${entry.id}`, entry);
      const index = blogEntries.value.findIndex((e) => e.id === entry.id);
      if (index !== -1) blogEntries.value[index] = entry;
    }
    closeEntryDialog();
  } catch (error) {
    console.error("Error saving blog entry:", error);
  }
};

const deleteEntry = async (entry: BlogEntry) => {
  try {
    const confirmation = confirm(`Are you sure you want to delete the entry "${entry.title}"?`);
    if (!confirmation) return;
    await $axios.delete(`/blog-entries/${entry.id}`);
    blogEntries.value = blogEntries.value.filter((e) => e.id !== entry.id);
  } catch (error) {
    console.error("Error deleting blog entry:", error);
  }
};

useHead({
  title: `Blog Detail`,
  meta: [{ name: "description", content: "This the blog's detail" }],
  bodyAttrs: {
    class: "blog-detail",
  },
  script: [{}],
});

onMounted(() => {
  fetchBlogDetails();
});
</script>
