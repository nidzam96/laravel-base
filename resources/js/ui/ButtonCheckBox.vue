/**
 * @author      : Nazrol - nazrol.zawawi@gmail.com
 * @description : VueJS custom checkbox
 */

<template>
  <button 
      type="button" 
      class="btn btn-light d-flex align-items-center py-1 px-1" 
      :class="cssClass" 
      v-on:click="action()"
      :disabled="disabled"
    >
      <div class="p-1 rounded border bg-white">
          <i class="fas fa-check mx-1" :class="{'text-white': !checkbox_value, 'text-success': checkbox_value}"></i>
      </div>
      <div class="pl-2 pr-1">{{ label }}</div>
  </button>
</template>
<script>

export default {
  props: [
    'label',
    'disabled',
    'value',
    'cssClass'
  ],

  data() {
    return {
      'checkbox_value': this.value
    };
  },

  mounted: function () {

    if (!this.value) {
      this.checkbox_value = false;
    }
  },

  computed: {

  },

  watch: {

    checkbox_value() {
      this.$emit('input', this.checkbox_value);
    }

  },

  methods: {

    action() {
      this.checkbox_value = !this.checkbox_value;
    }

  },

  destroyed: function () {

  }
}
</script>
