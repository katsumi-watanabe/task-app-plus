<template>
  <v-footer color="indigo" app>
    Footer
    <button @click="$emit('homePage')">ホームページに飛びます</button>
    <button @click="$emit('categoryPage')">カテゴリページに飛びます</button>
  </v-footer>
</template>
