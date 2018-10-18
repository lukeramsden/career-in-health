<template>
    <div class="container-fluid m-0 p-0">
        <template v-if="loaded">
            <div class="row" id="search-row">
                <div class="col-12 col-md-5 col-lg-4 order-md-last" id="search-form-parent">
                    <div id="search-form">
                    </div>
                </div>
                <div class="col-12 col-md-7 col-lg-8" id="search-results-parent">
                    <div id="search-results">
                    </div>
                </div>
            </div>
        </template>
        <loading-icon v-else></loading-icon>
    </div>
</template>

<script>
    import 'awesomplete';
    import 'awesomplete/awesomplete.css';
    import 'wnumb';
    import 'nouislider';
    import 'nouislider/distribute/nouislider.css';

    export default {
        data() {
            return {
                page: 0,
                lastPage: 0,
                perPage: 10,
                loaded: false,
            };
        },
    }
</script>

<style lang="scss">
    .noUi-tooltip {
        display: none;
    }

    .noUi-active .noUi-tooltip {
        display: block;

    }

    .noUi-handle {
        outline: none;
        border-radius: 0;
    }

    .noUi-value-sub {
        color: #999;
        line-height: 1.8;
    }

    .awesomplete {
        display: block;
    }
</style>