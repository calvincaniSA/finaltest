<template>
  <v-card class="mx-auto" max-width="800">
    <v-card-title>{{ action === "edit" ? "Edit Blog" : "Create New Blog" }}</v-card-title>
    <v-card-text>
      <v-form v-model="valid">
        <v-text-field
          v-model="blogData.title"
          label="Blog Title"
          :rules="[rules.required]"
          outlined
          required
        ></v-text-field>
        <v-textarea
          v-model="blogData.description"
          label="Blog Description"
          :rules="[rules.required]"
          outlined
          required
        ></v-textarea>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn :disabled="!valid" color="primary" @click="submitBlog">
        {{ action === "edit" ? "Save" : "Create Blog" }}
      </v-btn>
      <v-btn color="secondary" @click="cancel">Cancel</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import type { Blog } from "~/types/blog";

// emits
const emit = defineEmits(["save", "cancel"]);

// state
const valid = ref(false);
const blogData = ref<Blog>({ title: "", description: "" });

// rules
const rules = {
  required: (value: string) => !!value || "This field is required",
};

// props
const props = defineProps({
  action: {
    type: String,
    default: "create",
  },
  blog: {
    type: Object as () => Blog,
    default: () => ({
      title: "",
      description: "",
    }),
  },
});

// watchers
watch(
  () => props.blog,
  (newBlog) => {
    blogData.value = { ...newBlog };
  },
  { immediate: true }
);

// functions
const submitBlog = () => {
  const blogToSave = { ...blogData.value };

  if (blogToSave.id) {
    emit("save", blogToSave);
  } else {
    const { id, ...blogWithoutId } = blogToSave;
    emit("save", blogWithoutId);
  }
};

const cancel = () => {
  emit("cancel");
};
</script>
