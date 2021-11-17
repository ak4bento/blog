<template>
    <div v-cloak class="modal fade mh-100" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="input-group">
                        <div class="input-group-prepend mr-0 border-0">
                            <div
                                class="input-group-text pr-0 border-0"
                                :style="results.length > 0 ? 'border-radius: 0' : ''"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    width="20"
                                    class="icon-search"
                                >
                                    <circle cx="10" cy="10" r="7" style="fill: none" />
                                    <path
                                        class="fill-muted"
                                        d="M16.32 14.9l1.1 1.1c.4-.02.83.13 1.14.44l3 3a1.5 1.5 0 0 1-2.12 2.12l-3-3a1.5 1.5 0 0 1-.44-1.14l-1.1-1.1a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <vue-fuse
                            :keys="['name']"
                            :list="search.searchIndex"
                            :default-all="false"
                            :include-score="true"
                            :style="results.length > 0 ? 'border-radius: 0' : ''"
                            class="form-control form-control-lg border-0"
                            :placeholder="trans.search_canvas"
                            event-name="search"
                        />
                    </div>

                    <div :key="entity.item.id" v-for="entity in results">
                        <router-link
                            :to="{
                                name: entity.item.route,
                                params: { id: entity.item.id },
                            }"
                            class="text-decoration-none"
                            data-dismiss="modal"
                            @click="clearResults()"
                        >
                            <div v-hover="{ class: `hover-bg` }" class="p-3">
                                <div class="d-flex align-items-center">
                                    <div class="mr-auto pl-2 col-md-8 col-sm-10 col-10">
                                        <p class="mb-0 py-1 text-truncate">
                                            <span class="font-weight-bold text-lg lead">
                                                {{ entity.item.name }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="ml-auto d-md-inline-block">
                                        <span class="mr-3 text-muted">{{ entity.item.type }}</span>
                                    </div>

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="25"
                                        viewBox="0 0 24 24"
                                        class="icon-cheveron-right-circle"
                                    >
                                        <circle cx="12" cy="12" r="10" style="fill: none" />
                                        <path
                                            class="fill-light-gray"
                                            d="M10.3 8.7a1 1 0 0 1 1.4-1.4l4 4a1 1 0 0 1 0 1.4l-4 4a1 1 0 0 1-1.4-1.4l3.29-3.3-3.3-3.3z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex';
import Hover from '../../directives/Hover';
import VueFuse from 'vue-fuse';
import isEmpty from 'lodash/isEmpty';

export default {
    name: 'search-modal',

    components: {
        VueFuse,
    },

    directives: {
        Hover,
    },

    data() {
        return {
            results: [],
        };
    },

    computed: {
        ...mapState(['search']),
        ...mapGetters({
            trans: 'settings/trans',
        }),
    },

    created() {
        if (isEmpty(this.search.searchIndex)) {
            this.$store.dispatch('search/buildIndex');
        }
    },

    mounted() {
        this.$on('search', (results) => {
            this.results = results;
        });
    },

    methods: {
        clearResults() {
            this.results = [];
        },
    },
};
</script>
