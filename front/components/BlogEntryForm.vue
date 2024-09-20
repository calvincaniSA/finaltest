<template>
  <v-card class="mx-auto" max-width="600">
    <v-card-title>{{ action === "edit" ? "Edit Blog Entry" : "Create New Blog Entry" }}</v-card-title>
    <v-card-text>
      <v-form v-model="valid">
        <v-text-field
          v-model="entry.title"
          label="Entry Title"
          :rules="[rules.required]"
          outlined
          required
        ></v-text-field>
        <v-textarea
          v-model="entry.content"
          label="Entry Content"
          :rules="[rules.required]"
          outlined
          required
        ></v-textarea>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn :disabled="!valid" color="primary" @click="submitEntry">
        {{ action === "edit" ? "Save Entry" : "Create Entry" }}
      </v-btn>
      <v-btn color="secondary" @click="$emit('cancel')">Cancel</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
import { ref } from "vue";
import type { BlogEntry } from "~/types/blog";

const props = defineProps<{
  entry?: BlogEntry;
  action: "create" | "edit";
  blogId: number;
}>();

const emit = defineEmits<{
  (e: "save", entry: BlogEntry): void;
  (e: "cancel"): void;
}>();

const valid = ref(false);

const rules = {
  required: (value: string) => !!value || "This field is required",
};

const entry = ref<BlogEntry>({
  id: props.entry?.id, 
  title: props.entry?.title ?? "",
  content: props.entry?.content ?? "",
  blog_id: props.blogId,
  comments: props.entry?.comments ?? [],
});

const submitEntry = () => {
  if (valid.value) {
    const submittedEntry = { ...entry.value };

    if (props.action === "create") {
      delete submittedEntry.id;
    }

    emit("save", submittedEntry);
  }
};
</script>
