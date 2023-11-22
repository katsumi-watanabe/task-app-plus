import './bootstrap';
import { createApp } from 'vue';
import router from './router'
import PreventDoubleClickDirective from './directives/prevent-double-click.js';

import App from './App.vue';

// Vuetify 使用時に必要なライブラリのインポート
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

// lodash ライブラリのインポート
import _ from 'lodash'

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
// Vueアプリケーションインスタンスの作成
const app = createApp(App);
app.directive('prevent-double-click', PreventDoubleClickDirective);
app.use(router);
app.use(vuetify);
app.mount('#app');
