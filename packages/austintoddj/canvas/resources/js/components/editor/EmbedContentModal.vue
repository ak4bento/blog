<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div ref="modal" class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <h5 class="modal-title">{{ trans.embed_content }}</h5>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                        @click.prevent="closeModal"
                    >
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
                            <textarea
                                v-model="content"
                                id="embed"
                                rows="6"
                                name="embed"
                                style="resize: none"
                                class="form-control border-0"
                                :placeholder="trans.paste_embed_code_to_include"
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-link btn-block text-muted font-weight-bold text-decoration-none"
                        data-dismiss="modal"
                        @click="clickDone"
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
import isEmpty from 'lodash/isEmpty';

export default {
    name: 'embed-content-modal',

    data() {
        return {
            blot: null,
            content: null,
        };
    },

    computed: {
        ...mapGetters({
            trans: 'settings/trans',
        }),
    },

    mounted() {
        this.$parent.$on('opening-embed-content-modal', (data) => {
            if (!isEmpty(data)) {
                this.blot = data.existingBlot;
                this.content = data.content;
            }
        });
    },

    methods: {
        clickDone() {
            if (!isEmpty(this.content)) {
                this.$emit('adding-embed-content', {
                    content: this.content,
                    existingBlot: this.blot,
                });
            }

            this.blot = null;
            this.content = '';
        },

        closeModal() {
            this.blot = null;
            this.content = null;
            this.$refs.modal.hide;
        },
    },
};
</script>
