<template>
    <section>
        <page-header>
            <template slot="options">
                <div class="dropdown">
                    <a
                        id="navbarDropdown"
                        class="nav-link pr-0"
                        href="#"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="25"
                            class="icon-dots-horizontal"
                        >
                            <path
                                class="fill-light-gray"
                                fill-rule="evenodd"
                                d="M5 14a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm7 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm7 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"
                            />
                        </svg>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <router-link :to="{ name: 'create-user' }" class="dropdown-item">
                            {{ trans.new_user }}
                        </router-link>
                    </div>
                </div>
            </template>
        </page-header>

        <main class="py-4">
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                <div class="my-3">
                    <h3 class="mt-3">{{ trans.users }}</h3>
                    <p class="mt-2 text-secondary">
                        {{ trans.manage_user_roles }}
                    </p>
                </div>

                <div v-if="isReady" class="mt-5 card shadow-lg">
                    <div class="card-body p-0">
                        <div :key="`${index}-${user.id}`" v-for="(user, index) in users">
                            <router-link
                                :to="{
                                    name: 'edit-user',
                                    params: { id: user.id },
                                }"
                                class="text-decoration-none"
                            >
                                <div
                                    v-hover="{ class: `hover-bg` }"
                                    class="d-flex p-3 align-items-center"
                                    :class="{
                                        'border-top': index !== 0,
                                        'rounded-top': index === 0,
                                        'rounded-bottom': index === users.length - 1,
                                    }"
                                >
                                    <div class="pl-2 col-md-8 col-sm-10 col-10 py-1">
                                        <p class="mb-0 lead font-weight-bold text-truncate">
                                            {{ user.name }}
                                        </p>
                                        <p class="mb-1 text-secondary">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <div class="ml-auto d-none d-lg-inline pl-3">
                                        <div class="d-none d-md-inline">
                                            <span class="text-muted mr-3">
                                                {{ getRoleName(user.role) }}
                                            </span>
                                        </div>
                                        <img
                                            :src="user.avatar || gravatar(user.email)"
                                            style="width: 57px; height: 57px"
                                            class="mr-2 ml-3 shadow-inner rounded-circle"
                                            :alt="user.name"
                                        />
                                    </div>

                                    <div class="d-inline d-lg-none pl-3 ml-auto">
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

                        <infinite-loading spinner="spiral" @infinite="fetchUsers">
                            <span slot="no-more" />
                            <div slot="no-results" />
                        </infinite-loading>
                    </div>
                </div>
            </div>
        </main>
    </section>
</template>

<script>
import { mapGetters } from 'vuex';
import Hover from '../directives/Hover';
import InfiniteLoading from 'vue-infinite-loading';
import NProgress from 'nprogress';
import PageHeader from '../components/PageHeader';
import isEmpty from 'lodash/isEmpty';
import url from '../mixins/url';

export default {
    name: 'user-list',

    components: {
        InfiniteLoading,
        PageHeader,
    },

    directives: {
        Hover,
    },

    mixins: [url],

    data() {
        return {
            page: 1,
            users: [],
            isReady: false,
        };
    },

    computed: {
        ...mapGetters({
            trans: 'settings/trans',
        }),
    },

    async created() {
        await Promise.all([this.fetchUsers()]);
        this.isReady = true;
        NProgress.done();
    },

    methods: {
        fetchUsers($state) {
            if ($state) {
                return this.request()
                    .get('/api/users', {
                        params: {
                            page: this.page,
                        },
                    })
                    .then(({ data }) => {
                        if (!isEmpty(data) && !isEmpty(data.data)) {
                            this.page += 1;
                            this.users.push(...data.data);

                            $state.loaded();
                        } else {
                            $state.complete();
                        }

                        if (isEmpty($state)) {
                            NProgress.inc();
                        }
                    })
                    .catch(() => {
                        NProgress.done();
                    });
            }
        },

        getRoleName(id) {
            switch (id) {
                case 1:
                    return this.trans.contributor;
                case 2:
                    return this.trans.editor;
                case 3:
                    return this.trans.admin;
                default:
                    return '';
            }
        },
    },
};
</script>
