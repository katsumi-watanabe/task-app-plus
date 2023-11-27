<template>
  <v-footer color="indigo" app>
    Footer
    <button @click="$emit('homePage')">ホームページに飛びます</button>
    <button @click="$emit('searchPage')">検索ページに飛びます</button>
  </v-footer>
</template>
