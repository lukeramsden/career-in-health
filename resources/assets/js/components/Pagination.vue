<template>
  <ul v-if="show" class="pagination">
    <li v-if="onFirstPage"
        class="page-item page-item-previous disabled">
      <a class="page-link" tabindex="-1" href="javascript:">&lt;</a>
    </li>
    <li v-else
        :class="{disabled}"
        class="page-item page-item-previous">
      <a class="page-link" rel="prev" @click.prevent="prevPage()">&lt;</a>
    </li>

    <template v-for="(el, idx) in elements">
      <!-- "Three Dots" Separator -->
      <template v-if="_.isString(el)">
        <li :key="idx" class="page-item page-item-ellipses disabled">
          <a class="page-link" href="javascript:">{{ el }}</a>
        </li>
      </template>
      <template v-else-if="_.isNumber(el)">
        <li v-if="el === value" :key="idx"
            class="page-item page-item-active">
          <a class="page-link" href="javascript:">{{ el + 1 }}</a>
        </li>
        <li v-else :key="idx"
            :class="{disabled}"
            class="page-item">
          <a class="page-link" href="javascript:" @click.prevent="input(el)">{{ el + 1 }}</a>
        </li>
      </template>
    </template>

    <li v-if="hasMorePages"
        :class="{disabled}"
        class="page-item page-item-next">
      <a class="page-link" rel="next" @click.prevent="nextPage()">&gt;</a>
    </li>
    <li v-else
        class="page-item page-item-next disabled">
      <a class="page-link" href="javascript:">&gt;</a>
    </li>

    <li v-if="customInput"
        :class="{disabled}"
        class="page-item page-item-input">
      <input :value="value+1" class="form-control page-input" type="text"
             title="Page" @input="input($event.target.value-1)">
    </li>
  </ul>
</template>

<script>
// https://github.com/TahaSh/vue-paginate
export const LEFT_ARROW  = '<';
export const RIGHT_ARROW = '>';
export const ELLIPSES    = '…';

class LimitedLinksGenerator
{
  constructor( listOfPages, currentPage, limit )
  {
    this.listOfPages = listOfPages;
    this.lastPage    = listOfPages.length - 1;
    this.currentPage = currentPage === this.lastPage
      ? this.lastPage - 1
      : currentPage;
    this.limit       = limit;
  }

  generate()
  {
    const firstHalf = this._buildFirstHalf();
    const secondHalf  = this._buildSecondHalf();
    return [ ...firstHalf, ...secondHalf ];
  }

  _buildFirstHalf()
  {
    const firstHalf = this._allPagesButLast()
      .slice(
        this._currentChunkIndex(),
        this._currentChunkIndex() + this.limit,
      );

    // Add backward ellipses with first page if needed
    if ( this.currentPage >= this.limit )
    {
      firstHalf.unshift( ELLIPSES );
      firstHalf.unshift( 0 );
    }

    // Add ellipses if needed
    if ( this.lastPage - this.limit > this._currentChunkIndex() )
      firstHalf.push( ELLIPSES );

    return firstHalf;
  }

  _buildSecondHalf()
  {
    return [ this.lastPage ];
  }

  _currentChunkIndex()
  {
    return Math.floor( this.currentPage / this.limit ) * this.limit;
  }

  _allPagesButLast()
  {
    return this.listOfPages.filter( n => n !== this.lastPage );
  }
}

export default {
  props: {
    value: {
      type: Number,
    },
    lastPage: {
      type: Number,
      required: true,
    },
    firstPage: {
      type: Number,
      default: 0,
    },
    linksPerSide: {
      type: Number,
      default: 4,
    },
    customInput: {
      type: Boolean,
    },
    disabled: {
      type: Boolean,
    },
  },
  computed: {
    show()
    {
      return this.lastPage > this.firstPage;
    },
    onFirstPage()
    {
      return this.value === this.firstPage;
    },
    onLastPage()
    {
      return this.value === this.lastPage;
    },
    hasMorePages()
    {
      return this.value < this.lastPage;
    },
    hasPages()
    {
      return this.value !== 1 || this.hasMorePages;
    },
    elements()
    {
      return new LimitedLinksGenerator(
        _.range( this.firstPage, this.lastPage + 1 ),
        this.value,
        this.linksPerSide,
      ).generate();
    },
  },
  methods: {
    input( i )
    {
      if ( this.disabled ) return;

      this.$emit( 'input', _.clamp( i, this.firstPage, this.lastPage ) );
    },
    prevPage()
    {
      this.input( this.value - 1 );
    },
    nextPage()
    {
      this.input( this.value + 1 );
    },
  },
};
</script>

<style lang="scss">

</style>
