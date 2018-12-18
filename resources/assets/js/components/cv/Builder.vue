<template>
  <div class="container-fluid mt-4 mt-lg-5">
    <template v-if="loaded">
      <pre>{{ cv }}</pre>
    </template>
    <loading-icon v-else />
  </div>
</template>

<script>
export default {
  data()
  {
    return {
      cv: {},

      loaded: false,
    };
  },
  computed: {},
  mounted()
  {
    this.load().then( () =>
    {
      this.loaded = true;

      this.$nextTick( () =>
      {
        // DOM finished loading
      } );
    } );
  },
  methods: {
    async load()
    {
      const requests = [
        axios.get( route( 'cv.get.full' ) ),
      ];

      const results = ( await Promise.all( requests ) ).map( r => r.data );

      this.$set( this, 'cv', results[ 0 ] );
    },
  },
};
</script>

<style scoped lang="scss">
  .cv-item {
    background-color: #fff;
    padding: 1rem;
    margin: 1rem;
    -webkit-box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);
    -moz-box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);
    box-shadow: 10px 29px 82px -21px rgba(0, 0, 0, 0.13);

    &-inner {
      display: inline-block;

      .cv-item-education & {
        p:first-child {
          font-size: 1rem;
        }

        p:not(first-child) {
          font-size: 0.85rem;
        }
      }
    }
  }
</style>
