<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <h5 class="modal-title">{{ trans.general_settings }}</h5>

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
                            <label for="slug" class="font-weight-bold text-uppercase text-muted small">{{
                                trans.slug
                            }}</label>
                            <a
                                v-tooltip="{ placement: 'right' }"
                                v-if="post.title"
                                href="#"
                                class="text-decoration-none"
                                :title="trans.sync_with_post_title"
                                @click.prevent="syncSlug()"
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
                            <input
                                v-model="post.slug"
                                id="slug"
                                type="text"
                                class="form-control border-0"
                                name="slug"
                                :title="trans.slug"
                                :placeholder="trans.a_unique_slug"
                                @input="update"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label for="settings" class="font-weight-bold text-uppercase text-muted small">{{
                                trans.summary
                            }}</label>
                            <textarea
                                v-model="post.summary"
                                id="settings"
                                rows="4"
                                name="summary"
                                style="resize: none"
                                class="form-control resize-none border-0"
                                :placeholder="trans.a_descriptive_summary"
                                @input="update"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="font-weight-bold text-uppercase text-muted small">{{ trans.topic }}</label>
                            <multiselect
                                v-model="post.topic"
                                :options="topics"
                                :placeholder="trans.select_a_topic"
                                :tag-placeholder="trans.add_a_new_topic"
                                :multiple="true"
                                :taggable="true"
                                :max="1"
                                label="name"
                                track-by="slug"
                                style="cursor: pointer"
                                @input="update"
                                @tag="addTopic"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="font-weight-bold text-uppercase text-muted small">{{ trans.tags }}</label>
                            <multiselect
                                v-model="post.tags"
                                :options="tags"
                                :placeholder="trans.select_some_tags"
                                :tag-placeholder="trans.add_a_new_tag"
                                :multiple="true"
                                :taggable="true"
                                label="name"
                                track-by="slug"
                                style="cursor: pointer"
                                @input="update"
                                @tag="addTag"
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
import { mapGetters, mapState } from 'vuex';
import Multiselect from 'vue-multiselect';
import Tooltip from '../../directives/Tooltip';
import debounce from 'lodash/debounce';
import strings from '../../mixins/strings';

export default {
    name: 'settings-modal',

    components: {
        Multiselect,
    },

    directives: {
        Tooltip,
    },

    mixins: [strings],

    props: {
        post: {
            type: Object,
            required: true,
        },

        tags: {
            type: Array,
            default: () => [],
        },

        topics: {
            type: Array,
            default: () => [],
        },
    },

    computed: {
        ...mapState(['settings']),
        ...mapGetters({
            trans: 'settings/trans',
        }),
    },

    methods: {
        syncSlug() {
            this.$emit('sync-slug', strings.methods.slugify(this.post.title));
            this.update();
        },

        addTag(string) {
            let tag = {
                name: string,
                slug: strings.methods.slugify(string),
                user_id: this.settings.user.id,
            };

            this.$emit('add-post-tag', tag);
            this.$emit('add-tag', tag);
            this.update();
        },

        addTopic(string) {
            let topic = {
                name: string,
                slug: strings.methods.slugify(string),
                user_id: this.settings.user.id,
            };

            this.$emit('add-post-topic', topic);
            this.$emit('add-topic', topic);
            this.update();
        },

        update: debounce(function () {
            this.$emit('update-post');
        }, 3000),
    },
};
</script>
