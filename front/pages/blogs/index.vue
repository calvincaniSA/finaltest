<template>
  <div id="blogsPage" class="page">
    <v-container class="d-flex justify-center" fluid>
      <v-sheet elevation="0" class="p-2 w-2/4">
        <v-row class="align-center justify-space-between mb-1">
          <v-col cols="auto">
            <h1 class="text-h4 font-weight-black mb-0">Blogs</h1>
          </v-col>
          <v-col cols="auto">
            <v-btn class="ma-3" color="success" @click="toggleAddBlog" v-if="store.isAuthenticated">
              Add New Blog
            </v-btn>
          </v-col>
        </v-row>
        <p class="mb-5">Here is a list of blogs already created.</p>
        <v-row>
          <v-col cols="12" v-for="blog in blogs" :key="blog.id">
            <v-card class="mb-3">
              <v-card-title>{{ blog.title }}</v-card-title>
              <v-card-text>{{ blog.description }}</v-card-text>
              <v-card-actions>
                <v-btn v-if="blog.id !== undefined" color="primary" @click="viewBlog(blog.id)">View Blog</v-btn>
                <v-btn color="warning" @click="editBlog(blog)" v-if="store.isAuthenticated">Edit Blog</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>

        <v-dialog v-model="show" max-width="600px" persistent>
          <BlogForm :blog="blog" :action="action" @save="saveBlog" @cancel="closeModal" />
        </v-dialog>
      </v-sheet>
    </v-container>
  </div>
</template>

<script setup lang="ts">
// imports
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import BlogForm from "~/components/BlogForm.vue";
import type { Blog } from "~/types/blog";

// axios
const { $axios } = useNuxtApp();

// stores
const store = useAuthStore();

// router
const router = useRouter();

// state
const show = ref(false);
const action = ref<string>("create"); // State to track the action (create/edit)
const blogs = ref<Blog[]>([]);
const blog = ref<Blog>({
  id: 0,
  title: "",
  description: "",
});

// functions
const fetchBlogs = async () => {
  try {
    const response = await $axios.get("/blogs");
    blogs.value = response.data as Blog[];
  } catch (error) {
    console.error("Error fetching blogs:", error);
  }
};

const toggleAddBlog = () => {
  blog.value = { id: 0, title: "", description: "" };
  action.value = action.value === "create" ? "edit" : "create";
  show.value = !show.value;
};

const editBlog = (selectedBlog: Blog) => {
  action.value = "edit";
  blog.value = { ...selectedBlog };
  show.value = true;
};

const saveBlog = async (data: Blog) => {
  if (action.value === "edit" && data.id) {
    try {
      const response = await $axios.put(`/blogs/${data.id}`, data);
      const index = blogs.value.findIndex((b) => b.id === data.id);
      if (index !== -1) {
        blogs.value[index] = response.data;
      }
    } catch (error) {
      console.error("Error updating blog:", error);
    }
  } else {
    try {
      const { id, ...blogWithoutId } = data;
      const response = await $axios.post("/blogs", blogWithoutId);
      blogs.value.push(response.data);
    } catch (error) {
      console.error("Error creating blog:", error);
    }
  }
  closeModal();
};

const closeModal = () => {
  show.value = false;
};

const viewBlog = (blogId: number) => {
  router.push(`/blogs/${blogId}`);
};

useHead({
  title: "Blog",
  meta: [{ name: "description", content: "Blog" }],
  bodyAttrs: {
    class: "blog",
  },
  script: [{}],
});

// hooks
onMounted(() => {
  fetchBlogs();
});
</script>
