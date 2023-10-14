import { defineStore } from 'pinia'

export const useLayoutStore = defineStore('layout', {
  state: () => ({
    isAsideMobileExpanded: false
  }),

  actions: {
    asideMobileToggle () {
      this.isAsideMobileExpanded = !this.isAsideMobileExpanded
    }
  }
})
