<template>
  <div>
    <v-container class="d-flex justify-center" fluid>
      <v-sheet elevation="0" class="p-4 w-2/4">
        <h1 class="text-center text-h4 font-weight-black mb-5">About Blog Application</h1>
        <p>
          This is a tiny blog application that allows you to create blogs, view a list of blogs, view individual blogs,
          view blog entries and view comments made on blog entries. Everything is editable except the comments.
        </p>
      </v-sheet>
    </v-container>
  </div>
</template>

<script setup>
useHead({
  title: "About Level 7 Test",
  meta: [{ name: "description", content: "This is a test for a job application." }],
  bodyAttrs: {
    class: "about",
  },
  script: [{}],
});
</script>
