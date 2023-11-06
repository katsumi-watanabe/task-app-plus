import './bootstrap';
import { createApp } from 'vue';

import Task from './tasks.vue';

// Vuetify 使用時に必要なライブラリのインポート
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

// jsにアイコンフォント(mdi)を埋め込む場合に必要
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader

// Vuetifyの初期設定。最低限の設定 + mdiアイコン設定
const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  }
});

// use(vuetify)の追加
createApp(Task).use(vuetify).mount('#app');
