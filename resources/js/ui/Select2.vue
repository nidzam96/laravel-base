<template>
  <select>
    <slot></slot>
  </select>
</template>
<script>
  export default {
    props: [
      'options', 
      'value', 
      'multiple', 
      'width', 
      'adapter'
    ],

    data() {
      return {
        'select_options' : []
      };
    },

    mounted: function () {
      var vm = this
        // init select2
      this.select_options = this.options;

      $(this.$el)
      .select2({ 
        theme     : 'bootstrap4',
        width     : this.width,
        data      : this.select_options,
        multiple  : this.multiple
      })
      .val(this.value)
      .trigger('change')
      // emit event on change.
      .on('change', function () {
        vm.$emit('input', $(this).val())
      })

      // if data adapter is available
      if (typeof this.adapter !== 'undefined' && typeof this.adapter.url !== 'undefined') {
        let params = {};
        if (typeof this.adapter.params !== 'undefined') {
          params = this.adapter.params;
        }

        //console.log('adapter', this.adapter);

        axios
        .post(this.adapter.url, params)
        .then(response => {
          this.select_options = response.data;

          $(this.$el)
          .empty()
          .select2({ 
            theme     : 'bootstrap4',
            width     : this.width,
            data      : this.select_options,
            multiple  : this.multiple
          })
          .val(this.value)
          .trigger('change')
          .on('change', function () {
            vm.$emit('input', $(this).val())
          })

        });
      }
    },
    watch: {
      value: function (value) {
        // check to see if the arrays contain the same values
        if ([...value].sort().join(",") !== [...$(this.$el).val()].sort().join(",")) {
          // update value
          $(this.$el)
            .val(value)
            .trigger('change')
        }
      },
      options: function (options) {
        var vm = this;
        // update options

        this.select_options = this.options;

        $(this.$el)
        .empty()
        .select2({ 
          theme     : 'bootstrap4',
          width     : this.width,
          data      : this.select_options,
          multiple  : this.multiple
        })
        .val(this.value)
        .trigger('change')
        .on('change', function () {
          vm.$emit('input', $(this).val())
        })
      }
    },
    destroyed: function () {
      $(this.$el).off().select2('destroy')
    }
  }
</script>
