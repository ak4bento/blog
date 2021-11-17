<template>
    <section>
        <page-header>
            <template slot="options">
                <div v-if="!creatingUser && !authProfile" class="dropdown">
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
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a href="#" class="dropdown-item text-danger" @click="showDeleteModal"> {{ trans.delete }} </a>
                    </div>
                </div>
            </template>
        </page-header>

        <main v-if="isReady" class="py-4">
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pb-2">
                        <h3 class="mt-3">
                            <span v-if="isAdmin">
                                <router-link :to="{ name: 'users' }" class="text-decoration-none text-muted">
                                    {{ trans.users }}
                                </router-link>
                                <span class="text-muted"> / </span>
                            </span>

                            {{ title }}
                        </h3>
                        <p v-if="!creatingUser" class="mt-2 text-secondary">
                            {{ trans.last_updated }} {{ moment(user.updated_at).fromNow() }}
                        </p>
                    </div>
                    <select
                        v-model="user.role"
                        id="role"
                        v-if="!creatingUser"
                        :disabled="authProfile"
                        class="ml-auto w-auto custom-select border-0 bg-light"
                        name="role"
                        @change="selectRole"
                    >
                        <option
                            :key="`${roleId}-${name}`"
                            v-for="(name, roleId) in settings.roles"
                            :value="roleId"
                            :selected="user.role === roleId"
                        >
                            {{ name }}
                        </option>
                    </select>
                </div>

                <div v-if="!creatingUser" class="mt-4 card shadow-lg">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-12 col-md-3 px-0 text-center">
                                <img
                                    :src="user.avatar || user.default_avatar"
                                    :alt="user.name"
                                    class="avatar align-self-center mb-4 mb-md-0 rounded-circle"
                                    @click="showAvatarModal"
                                />
                            </div>
                            <div class="col-12 col-md-9 align-self-center px-0 text-center text-md-left">
                                <h5 class="mt-0 mb-1 font-weight-bold">{{ user.name }}</h5>
                                <a class="mb-1 text-primary text-decoration-none" :href="`mailto:${user.email}`">{{
                                    user.email
                                }}</a>
                                <p class="text-secondary mb-0">
                                    {{ numberOfPosts }} ― Created {{ moment(user.created_at).fromNow() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 card shadow-lg">
                    <div class="card-body">
                        <div class="col-12">
                            <div class="form-group row">
                                <label for="name" class="font-weight-bold text-uppercase text-muted small">
                                    {{ trans.name }}
                                </label>
                                <input
                                    v-model="user.name"
                                    id="name"
                                    name="name"
                                    type="text"
                                    required
                                    class="form-control border-0"
                                    :class="invalidName.shouldShow ? 'is-invalid' : ''"
                                    :title="trans.name"
                                    :placeholder="trans.name"
                                />
                                <span v-if="invalidName.shouldShow" class="invalid-feedback" role="alert">
                                    <strong>{{ invalidName.error }}</strong>
                                </span>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="font-weight-bold text-uppercase text-muted small">
                                    Email
                                </label>
                                <input
                                    v-model="user.email"
                                    id="email"
                                    required
                                    name="email"
                                    type="email"
                                    class="form-control border-0"
                                    :class="invalidEmail.shouldShow ? 'is-invalid' : ''"
                                    title="Email"
                                    placeholder="Email"
                                />
                                <span v-if="invalidEmail.shouldShow" class="invalid-feedback" role="alert">
                                    <strong>{{ invalidEmail.error }}</strong>
                                </span>
                            </div>
                            <div v-if="!creatingUser" class="form-group row">
                                <label for="username" class="font-weight-bold text-uppercase text-muted small">
                                    {{ trans.username }}
                                </label>
                                <input
                                    v-model="user.username"
                                    id="username"
                                    name="username"
                                    type="text"
                                    class="form-control border-0"
                                    :class="invalidUsername.shouldShow ? 'is-invalid' : ''"
                                    title="Username"
                                    :placeholder="trans.choose_a_username"
                                />
                                <span v-if="invalidUsername.shouldShow" class="invalid-feedback" role="alert">
                                    <strong>{{ invalidUsername.error }}</strong>
                                </span>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="font-weight-bold text-uppercase text-muted small">
                                    Password
                                </label>
                                <input
                                    v-model="user.password"
                                    id="password"
                                    :required="creatingUser"
                                    name="password"
                                    type="password"
                                    class="form-control border-0"
                                    :class="invalidPassword.shouldShow ? 'is-invalid' : ''"
                                    title="Password"
                                    placeholder="Password"
                                />
                                <span v-if="invalidPassword.shouldShow" class="invalid-feedback" role="alert">
                                    <strong>{{ invalidPassword.error }}</strong>
                                </span>
                            </div>
                            <div class="form-group row">
                                <label
                                    for="password_confirmation"
                                    class="font-weight-bold text-uppercase text-muted small"
                                >
                                    Confirm Password
                                </label>
                                <input
                                    v-model="user.password_confirmation"
                                    id="password_confirmation"
                                    :required="creatingUser"
                                    name="password_confirmation"
                                    type="password"
                                    class="form-control border-0"
                                    title="Confirm Password"
                                    placeholder="Confirm Password"
                                />
                            </div>
                            <div v-if="!creatingUser" class="form-group row">
                                <label for="summary" class="font-weight-bold text-uppercase text-muted small">
                                    {{ trans.summary }}
                                </label>
                                <textarea
                                    v-model="user.summary"
                                    id="summary"
                                    rows="4"
                                    name="summary"
                                    style="resize: none"
                                    class="form-control border-0"
                                    :placeholder="trans.tell_us_about_yourself"
                                />
                            </div>

                            <div class="form-group row mt-4 mb-2">
                                <div class="col-md px-0">
                                    <a
                                        href="#"
                                        onclick="this.blur()"
                                        :disabled="shouldDisableSaveButton"
                                        class="btn btn-success btn-block font-weight-bold mt-0"
                                        :class="{ disabled: shouldDisableSaveButton }"
                                        aria-label="Save"
                                        @click.prevent="saveUser"
                                    >
                                        {{ trans.save }}
                                    </a>
                                </div>
                                <div class="col-md px-0">
                                    <router-link
                                        :to="{ name: 'users' }"
                                        class="btn btn-link btn-block font-weight-bold text-muted text-decoration-none"
                                    >
                                        {{ trans.cancel }}
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 v-if="posts.length > 0" class="mt-5">{{ trans.posts }}</h2>

                <div v-if="posts.length > 0" class="mt-3 card shadow-lg">
                    <div class="card-body p-0">
                        <div :key="`${index}-${post.id}`" v-for="(post, index) in posts">
                            <router-link
                                :to="{
                                    name: 'edit-post',
                                    params: { id: post.id },
                                }"
                                class="text-decoration-none"
                            >
                                <div
                                    v-hover="{ class: `hover-bg` }"
                                    class="d-flex p-3 align-items-center"
                                    :class="{
                                        'border-top': index !== 0,
                                        'rounded-top': index === 0,
                                        'rounded-bottom': index === posts.length - 1,
                                    }"
                                >
                                    <div class="pl-2 col-md-6 col-sm-8 col-10">
                                        <p class="mb-0 mt-2 lead font-weight-bold text-truncate">
                                            {{ post.title }}
                                        </p>
                                        <p class="text-secondary mb-2">
                                            <span v-if="isPublished(post.published_at)">
                                                <span class="d-none d-md-inline"> {{ post.read_time }} ― </span>
                                                {{ trans.published }}
                                                {{ moment(post.published_at).format('MMM D, YYYY') }}
                                            </span>
                                            <span v-if="isDraft(post.published_at)">
                                                <span class="text-danger">{{ trans.draft }}</span>
                                                <span class="d-none d-md-inline">
                                                    ― {{ trans.updated }}
                                                    {{ moment(post.updated_at).fromNow() }}
                                                </span>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="d-none d-md-inline">
                                            <span class="text-secondary mr-3"
                                                >{{ suffixedNumber(post.views_count) }}
                                                {{ post.views_count == 1 ? trans.view : trans.views }}</span
                                            >
                                            <span class="mr-3"
                                                >{{ trans.created }}
                                                {{ moment(post.created_at).format('MMM D, YYYY') }}</span
                                            >
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

                        <infinite-loading spinner="spiral" @infinite="fetchPosts">
                            <span slot="no-more" />
                            <div slot="no-results" />
                        </infinite-loading>
                    </div>
                </div>
            </div>
        </main>

        <section v-if="isReady">
            <avatar-modal ref="avatarModal" :user="user" @update-avatar="updateAvatar" @update="saveUser" />
            <delete-modal
                ref="deleteModal"
                :header="trans.delete"
                :message="trans.deleted_users_are_gone_forever"
                @delete="deleteUser"
            />
        </section>
    </section>
</template>

<script>
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import 'filepond/dist/filepond.min.css';
import { mapGetters, mapState } from 'vuex';
import $ from 'jquery';
import AvatarModal from '../components/modals/AvatarModal';
import DeleteModal from '../components/modals/DeleteModal';
import Hover from '../directives/Hover';
import InfiniteLoading from 'vue-infinite-loading';
import NProgress from 'nprogress';
import PageHeader from '../components/PageHeader';
import isEmpty from 'lodash/isEmpty';
import status from '../mixins/status';
import strings from '../mixins/strings';
import url from '../mixins/url';

export default {
    name: 'edit-user',

    components: {
        AvatarModal,
        DeleteModal,
        InfiniteLoading,
        PageHeader,
    },

    directives: {
        Hover,
    },

    mixins: [url, status, strings],

    data() {
        return {
            uri: this.$route.params.id || 'create',
            user: {},
            posts: [],
            page: 1,
            errors: [],
            isReady: false,
        };
    },

    computed: {
        ...mapState(['settings']),
        ...mapGetters({
            isAdmin: 'settings/isAdmin',
            trans: 'settings/trans',
        }),

        authProfile() {
            return this.settings.user.id === this.user.id;
        },

        shouldDisableSaveButton() {
            if (this.creatingUser) {
                return isEmpty(this.user.name) || isEmpty(this.user.email) || isEmpty(this.user.password);
            } else {
                return isEmpty(this.user.name) || isEmpty(this.user.email);
            }
        },

        creatingUser() {
            return this.$route.name === 'create-user';
        },

        title() {
            if (this.creatingUser) {
                return this.user.name || this.trans.new_user;
            } else {
                return this.user.name || this.trans.edit_user;
            }
        },

        numberOfPosts() {
            let noun = this.user.posts_count === 1 ? this.trans.post : this.trans.posts;
            let count = this.user.posts_count || 0;

            return `${count} ${noun}`;
        },

        invalidName() {
            if (!isEmpty(this.errors.name) && this.errors.name.length > 0) {
                return {
                    error: this.errors.name[0],
                    shouldShow: true,
                };
            }

            return {
                error: null,
                shouldShow: false,
            };
        },

        invalidEmail() {
            if (!isEmpty(this.errors.email) && this.errors.email.length > 0) {
                return {
                    error: this.errors.email[0],
                    shouldShow: true,
                };
            }

            return {
                error: null,
                shouldShow: false,
            };
        },

        invalidUsername() {
            if (!isEmpty(this.errors.username) && this.errors.username.length > 0) {
                return {
                    error: this.errors.username[0],
                    shouldShow: true,
                };
            }

            return {
                error: null,
                shouldShow: false,
            };
        },

        invalidPassword() {
            if (!isEmpty(this.errors.password) && this.errors.password.length > 0) {
                return {
                    error: this.errors.password[0],
                    shouldShow: true,
                };
            }

            return {
                error: null,
                shouldShow: false,
            };
        },
    },

    watch: {
        async $route(to) {
            this.isReady = false;
            this.uri = to.params.id;
            await Promise.all([this.fetchUser(), this.fetchPosts()]);
            this.isReady = true;
            NProgress.done();
        },
    },

    async created() {
        await Promise.all([this.fetchUser(), this.fetchPosts()]);
        this.isReady = true;
        NProgress.done();
    },

    methods: {
        fetchUser() {
            return this.request()
                .get(`/api/users/${this.uri}`)
                .then(({ data }) => {
                    this.user = data;
                    NProgress.inc();
                })
                .catch(() => {
                    this.$router.push({ name: 'users' });
                });
        },

        fetchPosts($state) {
            return this.request()
                .get(`/api/users/${this.uri}/posts`, {
                    params: {
                        page: this.page,
                    },
                })
                .then(({ data }) => {
                    if (!isEmpty(data) && !isEmpty(data.data)) {
                        this.page += 1;
                        this.posts.push(...data.data);
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
        },

        async saveUser() {
            this.errors = [];

            await this.request()
                .post(`/api/users/${this.user.id}`, this.user)
                .then(({ data }) => {
                    this.user = data.user;

                    if (this.authProfile) {
                        this.$store.dispatch('settings/setUser', data.user);
                    }

                    this.$toasted.show(this.trans.saved, {
                        className: 'bg-success',
                    });
                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                });

            if (isEmpty(this.errors) && this.creatingUser) {
                await this.$router.push({ name: 'edit-user', params: { id: this.user.id } });
                NProgress.done();
            }
        },

        clearAvatar() {
            this.user.avatar = this.user.default_avatar;
            this.isReadyToAcceptUploads = true;
        },

        updateAvatar(url) {
            this.user.avatar = url;
        },

        selectRole() {
            this.request()
                .post(`/api/users/${this.user.id}`, this.user)
                .then(() => {
                    this.$toasted.show(this.trans.saved, {
                        className: 'bg-success',
                    });
                });
        },

        async deleteUser() {
            await this.request()
                .delete(`/api/users/${this.uri}`)
                .then(() => {
                    this.$store.dispatch('search/buildIndex', true);
                    this.$toasted.show(this.trans.success, {
                        className: 'bg-success',
                    });
                });

            $(this.$refs.deleteModal.$el).modal('hide');

            await this.$router.push({ name: 'users' });
        },

        showAvatarModal() {
            $(this.$refs.avatarModal.$el).modal('show');
        },

        showDeleteModal() {
            $(this.$refs.deleteModal.$el).modal('show');
        },
    },
};
</script>

<style scoped lang="scss">
@import '../../sass/utilities/variables';
img {
    width: 150px;
    cursor: pointer;
}

img:hover {
    box-shadow: 0 0 0 3px $green;
}
</style>
