import { createStore } from 'vuex';

export default createStore({
  state: {
    selectedStatus: false,
    selectedCategory: [],
    keywordSearch: '',
  },
  mutations: {
    setStatus(state, selectedStatus) {
      state.selectedStatus = selectedStatus;
    },
    setCategory(state, selectedCategory) {
      state.selectedCategory = selectedCategory;
    },
    setKeyword(state, keywordSearch) {
      state.keywordSearch = keywordSearch;
    },
  },
  actions: {
    updateStatus({ commit }, selectedStatus) {
      commit('setStatus', selectedStatus);
    },
    updateCategories({ commit }, selectedCategory) {
      commit('setCategory', selectedCategory);
    },
    updateKeyword({ commit }, keywordSearch) {
      commit('setKeyword', keywordSearch);
    },
  },
});
