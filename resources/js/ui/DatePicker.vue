/**
 * @author      : Nazrol - nazrol.zawawi@gmail.com
 * @description : VueJS custom component for datepicker based on v-calendar
 */

<template>
  <div>
    <input
      v-if="disabled"
      type="text"
      class="form-control"
      :class="cssClass"
      :value="value"
      placeholder=""
      :disabled="true"
    >
    <v-date-picker
      v-else
      :value="selected_date_object"
      v-on:input="onDatePicker"
      :class="cssClass"
      class="form-control p-0 custom-v-calendar"
      :theme="vc_theme"
      :input-props="{placeholder: placeholder, readonly: true}"
      :masks="{ input: ['DD/MM/YYYY']}"
      :from-date="default_date_object"
      :max-date="maxDate"
    />
    <span class="invalid-feedback" role="alert">
       {{ label }} is required
    </span>
  </div>
</template>
<script>

export default {
  props: [
    'label',
    'disabled',
    'cssClass',
    'theme',
    'value',
    'placeholder',
    'frompage',
    'maxDate',
  ],

  data() {
    return {
      "vc_theme"      : {
        "color": "red",
        "weekdays": {
          "light"   : 'vc-text-md vc-font-bold vc-text-gray-500',
          "dark"    : 'vc-text-md vc-font-bold vc-text-{color}-200',
        },
        "dayContent": 'vc-p-3 vc-font-medium vc-text-md vc-cursor-pointer focus:vc-font-bold vc-rounded-full'
      }
    };
  },

  mounted: function () {

  },

  computed: {
    selected_date_object() {
      if (this.value !== '') {
        // https://momentjs.com/docs/#/parsing/string-format/
        // The parser ignores non-alphanumeric characters, so both of the following will return the same thing.
        // moment("12/25/1995", "MM-DD-YYYY");

        let date = moment(this.value, "DD-MM-YYYY");
        return date.toDate();

      }
    },

    default_date_object() {
      if (this.frompage === undefined) {
        return this.frompage;
      }

      let date = moment(this.frompage, "DD-MM-YYYY");

      return date.toDate();
    }
  },

  watch: {
    /*value: function (value) {
      this.$emit('input', value);
    }*/
  },

  methods: {

    onDatePicker(value) {

      let date = moment(value);
      let date_string = date.format('DD/MM/YYYY');

      this.$emit('input', date_string);

    }
  },

  destroyed: function () {

  }
}
</script>
