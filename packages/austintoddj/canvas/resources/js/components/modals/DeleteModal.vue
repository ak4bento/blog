<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body py-4">
                    <p class="mb-1 lead font-weight-bold">
                        {{ header }}
                    </p>
                    <p class="text-secondary">
                        {{ message }}
                    </p>
                </div>
                <div class="modal-footer py-md-2 py-sm-0">
                    <div class="row w-100">
                        <div class="col-lg order-lg-last px-0 py-md-1 py-sm-1">
                            <a
                                href="#"
                                class="btn btn-danger btn-block font-weight-bold mt-0"
                                aria-label="Delete"
                                @click.prevent="confirmProceed()"
                            >
                                {{ trans.delete }}
                            </a>
                        </div>
                        <div class="col-lg order-lg-first px-0 py-md-1 py-sm-1 pt-2">
                            <button
                                class="btn btn-link btn-block font-weight-bold text-muted text-decoration-none"
                                data-dismiss="modal"
                            >
                                {{ trans.cancel }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'delete-modal',

    props: {
        header: {
            type: String,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
    },

    computed: {
        ...mapGetters({
            trans: 'settings/trans',
        }),
    },

    methods: {
        confirmProceed() {
            this.$emit('delete');
        },
    },
};
</script>
