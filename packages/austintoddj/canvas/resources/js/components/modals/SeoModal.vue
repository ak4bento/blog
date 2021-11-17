<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <h5 class="modal-title">{{ trans.seo_settings }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="24"
                            class="icon-close-circle"
                        >
                            <circle cx="12" cy="12" r="10" class="fill-light-gray" />
                            <path
                                class="fill-bg"
                                d="M13.41 12l2.83 2.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 1 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12z"
                            />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="title" class="font-weight-bold text-uppercase text-muted small">
                                {{ trans.meta_title }}
                                <a
                                    v-tooltip="{ placement: 'right' }"
                                    href="#"
                                    class="text-decoration-none"
                                    :title="trans.sync_with_post_title"
                                    @click.prevent="syncTitle"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        class="icon-refresh"
                                        width="25"
                                    >
                                        <circle cx="12" cy="12" r="10" style="fill: none" />
                                        <path
                                            class="fill-light-gray"
                                            d="M8.52 7.11a5.98 5.98 0 0 1 8.98 2.5 1 1 0 1 1-1.83.8 4 4 0 0 0-5.7-1.86l.74.74A1 1 0 0 1 10 11H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1.7-.7l.82.81zm5.51 8.34l-.74-.74A1 1 0 0 1 14 13h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1.7.7l-.82-.81A5.98 5.98 0 0 1 6.5 14.4a1 1 0 1 1 1.83-.8 4 4 0 0 0 5.7 1.85z"
                                        />
                                    </svg>
                                </a>
                            </label>
                            <input
                                v-model="post.meta.title"
                                id="title"
                                name="title"
                                type="text"
                                class="form-control border-0"
                                :title="trans.meta_title"
                                :placeholder="trans.meta_title_placeholder"
                                @input="update"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="description" class="font-weight-bold text-uppercase text-muted small">
                                {{ trans.meta_description }}
                                <a
                                    v-tooltip="{ placement: 'right' }"
                                    href="#"
                                    class="text-decoration-none"
                                    :title="trans.sync_with_post_description"
                                    @click.prevent="syncDescription"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        class="icon-refresh"
                                        width="25"
                                    >
                                        <circle cx="12" cy="12" r="10" style="fill: none" />
                                        <path
                                            class="fill-light-gray"
                                            d="M8.52 7.11a5.98 5.98 0 0 1 8.98 2.5 1 1 0 1 1-1.83.8 4 4 0 0 0-5.7-1.86l.74.74A1 1 0 0 1 10 11H7a1 1 0 0 1-1-1V7a1 1 0 0 1 1.7-.7l.82.81zm5.51 8.34l-.74-.74A1 1 0 0 1 14 13h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1.7.7l-.82-.81A5.98 5.98 0 0 1 6.5 14.4a1 1 0 1 1 1.83-.8 4 4 0 0 0 5.7 1.85z"
                                        />
                                    </svg>
                                </a>
                            </label>
                            <textarea
                                v-model="post.meta.description"
                                id="description"
                                rows="4"
                                name="description"
                                style="resize: none"
                                class="form-control border-0"
                                :placeholder="trans.meta_description_placeholder"
                                @input="update"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="canonical_link" class="font-weight-bold text-uppercase text-muted small">
                                {{ trans.canonical_link }}
                            </label>
                            <input
                                v-model="post.meta.canonical_link"
                                id="canonical_link"
                                type="text"
                                class="form-control border-0"
                                name="canonical_link"
                                :title="trans.canonical_link"
                                :placeholder="trans.canonical_link_placeholder"
                                @input="update"
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-link btn-block font-weight-bold text-muted text-decoration-none"
                        data-dismiss="modal"
                    >
                        {{ trans.done }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Tooltip from '../../directives/Tooltip';
import debounce from 'lodash/debounce';

export default {
    name: 'seo-modal',

    directives: {
        Tooltip,
    },

    props: {
        post: {
            type: Object,
            required: true,
        },
    },

    computed: {
        ...mapGetters({
            trans: 'settings/trans',
        }),
    },

    methods: {
        syncTitle() {
            this.$emit('sync-title', this.post.title);
            this.update();
        },

        syncDescription() {
            this.$emit('sync-description', this.post.summary);
            this.update();
        },

        update: debounce(function () {
            this.$emit('update-post');
        }, 3000),
    },
};
</script>
