<template>
  <div>
    <v-container class="d-flex justify-center" fluid>
      <v-sheet elevation="0" class="p-4 w-2/4">
        <h1 class="text-center text-h4 font-weight-black mb-5">Level Seven Blog Application</h1>
        <p>This is the final test in a series of tests to apply for a position at Level 7</p>
      </v-sheet>
    </v-container>
  </div>
</template>

<script setup>
useHead({
  title: "Level 7 Test",
  meta: [{ name: "description", content: "Level Seven Final Test." }],
  bodyAttrs: {
    class: "test",
  },
  script: [{}],
});
</script>
