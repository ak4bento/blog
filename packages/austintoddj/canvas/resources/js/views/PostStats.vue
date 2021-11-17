<template>
    <section>
        <page-header>
            <template slot="options" v-if="isReady && canEditPost">
                <div class="dropdown">
                    <a
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
                        <router-link
                            :to="{ name: 'edit-post', params: { id: $route.params.id } }"
                            class="dropdown-item"
                        >
                            {{ trans.edit_post }}
                        </router-link>
                    </div>
                </div>
            </template>
        </page-header>

        <main v-if="isReady" class="py-4">
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                <div class="my-3">
                    <h3 class="mt-3">{{ data.post.title }}</h3>
                    <p class="mt-2 text-secondary">
                        {{ trans.published }}
                        {{ moment(data.post.published_at).fromNow() }}
                    </p>
                </div>

                <div class="card-deck mt-5">
                    <div class="card shadow-lg">
                        <div class="card-body p-3">
                            <p class="lead border-bottom">
                                {{ trans.lifetime_summary }}
                            </p>
                            <div class="d-flex">
                                <div class="mr-5">
                                    <p class="mb-0 small text-muted text-uppercase font-weight-bold">
                                        {{ trans.total_views }}
                                    </p>
                                    <h3 class="mt-1">
                                        {{ suffixedNumber(data.totalViews) }}
                                    </h3>
                                </div>

                                <div>
                                    <p class="mb-0 small text-muted text-uppercase font-weight-bold">
                                        {{ trans.average_reading_time }}
                                    </p>
                                    <h3 class="mt-1">
                                        {{ data.readTime }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-lg">
                        <div class="card-body p-3">
                            <p class="lead border-bottom">
                                {{ trans.monthly_summary }}
                            </p>
                            <div class="d-flex">
                                <div class="mr-5">
                                    <p class="mb-0 small text-muted text-uppercase font-weight-bold">
                                        <a
                                            v-tooltip="{ placement: 'top' }"
                                            href="#"
                                            class="text-decoration-none"
                                            :title="trans.views_info"
                                        >
                                            {{ trans.views }}
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="17"
                                                viewBox="0 0 24 24"
                                                class="icon-help ml-1"
                                            >
                                                <path
                                                    class="fill-light-gray"
                                                    d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z"
                                                />
                                                <path
                                                    class="fill-bg"
                                                    d="M12 19.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.5a1 1 0 0 1-2 0v-1.41a1 1 0 0 1 .55-.9L14 10.5C14.64 10.08 15 9.53 15 9c0-1.03-1.3-2-3-2-1.35 0-2.49.62-2.87 1.43a1 1 0 0 1-1.8-.86C8.05 6.01 9.92 5 12 5c2.7 0 5 1.72 5 4 0 1.3-.76 2.46-2.05 3.24L13 13.2V14z"
                                                />
                                            </svg>
                                        </a>
                                    </p>
                                    <h3 class="mt-1 mb-2">
                                        {{ suffixedNumber(data.monthlyViews) }}
                                    </h3>
                                    <p class="small text-muted">
                                        <span v-if="viewsAreTrendingUp">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                width="17"
                                                class="icon-arrow-thick-up-circle mr-1"
                                            >
                                                <circle cx="12" cy="12" r="10" class="fill-light-gray" />
                                                <path
                                                    class="fill-bg"
                                                    d="M14 12v5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-5H8a1 1 0 0 1-.7-1.7l4-4a1 1 0 0 1 1.4 0l4 4A1 1 0 0 1 16 12h-2z"
                                                />
                                            </svg>
                                        </span>
                                        <span v-else>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                width="17"
                                                class="icon-arrow-thick-down-circle mr-1"
                                            >
                                                <circle cx="12" cy="12" r="10" class="fill-light-gray" />
                                                <path
                                                    class="fill-bg"
                                                    d="M10 12V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5h2a1 1 0 0 1 .7 1.7l-4 4a1 1 0 0 1-1.4 0l-4-4A1 1 0 0 1 8 12h2z"
                                                />
                                            </svg>
                                        </span>
                                        {{ data.monthOverMonthViews.percentage }}% {{ trans.from_last_month }}
                                    </p>
                                </div>

                                <div>
                                    <p class="mb-0 small text-muted text-uppercase font-weight-bold">
                                        <a
                                            v-tooltip="{ placement: 'top' }"
                                            href="#"
                                            class="text-decoration-none"
                                            :title="trans.visits_info"
                                        >
                                            {{ trans.visitors }}
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="17"
                                                viewBox="0 0 24 24"
                                                class="icon-help ml-1"
                                            >
                                                <path
                                                    class="fill-light-gray"
                                                    d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z"
                                                />
                                                <path
                                                    class="fill-bg"
                                                    d="M12 19.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.5a1 1 0 0 1-2 0v-1.41a1 1 0 0 1 .55-.9L14 10.5C14.64 10.08 15 9.53 15 9c0-1.03-1.3-2-3-2-1.35 0-2.49.62-2.87 1.43a1 1 0 0 1-1.8-.86C8.05 6.01 9.92 5 12 5c2.7 0 5 1.72 5 4 0 1.3-.76 2.46-2.05 3.24L13 13.2V14z"
                                                />
                                            </svg>
                                        </a>
                                    </p>
                                    <h3 class="mt-1 mb-2">
                                        {{ suffixedNumber(data.monthlyVisits) }}
                                    </h3>
                                    <p class="small text-muted">
                                        <span v-if="visitsAreTrendingUp">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                width="17"
                                                class="icon-arrow-thick-up-circle mr-1"
                                            >
                                                <circle cx="12" cy="12" r="10" class="fill-light-gray" />
                                                <path
                                                    class="fill-bg"
                                                    d="M14 12v5a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-5H8a1 1 0 0 1-.7-1.7l4-4a1 1 0 0 1 1.4 0l4 4A1 1 0 0 1 16 12h-2z"
                                                />
                                            </svg>
                                        </span>
                                        <span v-else>
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                width="17"
                                                class="icon-arrow-thick-down-circle mr-1"
                                            >
                                                <circle cx="12" cy="12" r="10" class="fill-light-gray" />
                                                <path
                                                    class="fill-bg"
                                                    d="M10 12V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5h2a1 1 0 0 1 .7 1.7l-4 4a1 1 0 0 1-1.4 0l-4-4A1 1 0 0 1 8 12h2z"
                                                />
                                            </svg>
                                        </span>
                                        {{ data.monthOverMonthVisits.percentage }}% {{ trans.from_last_month }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <line-chart :views="plotViewPoints" :visits="plotVisitPoints" class="mt-5 mb-3" />

                <div class="row justify-content-between">
                    <div class="col-md-6 mt-4">
                        <h5 class="text-muted small text-uppercase font-weight-bold pb-2">
                            {{ trans.views_by_traffic_source }}
                        </h5>

                        <div v-if="Object.keys(data.topReferers).length > 0">
                            <div :key="`${host}-${views}`" v-for="(views, host, index) in data.topReferers">
                                <div
                                    class="d-flex py-2 align-items-center px-2"
                                    :class="index % 2 === 0 ? 'bg-list-muted' : ''"
                                >
                                    <div class="mr-auto">
                                        <div v-if="host === trans.other">
                                            <p class="mb-0 py-1">
                                                <img
                                                    :src="getDefaultFavicon(host)"
                                                    :style="
                                                        settings.user.dark_mode === true
                                                            ? {
                                                                  filter: 'invert(100%)',
                                                              }
                                                            : ''
                                                    "
                                                    :alt="host"
                                                    width="15"
                                                    height="15"
                                                    class="mr-1"
                                                />
                                                <a
                                                    v-tooltip="{
                                                        placement: 'right',
                                                    }"
                                                    href="#"
                                                    class="text-decoration-none text-primary"
                                                    :title="trans.referer_unknown"
                                                >
                                                    {{ host }}
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="20"
                                                        viewBox="0 0 24 24"
                                                        class="icon-help"
                                                    >
                                                        <path
                                                            class="fill-light-gray"
                                                            d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20z"
                                                        />
                                                        <path
                                                            class="fill-bg"
                                                            d="M12 19.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm1-5.5a1 1 0 0 1-2 0v-1.41a1 1 0 0 1 .55-.9L14 10.5C14.64 10.08 15 9.53 15 9c0-1.03-1.3-2-3-2-1.35 0-2.49.62-2.87 1.43a1 1 0 0 1-1.8-.86C8.05 6.01 9.92 5 12 5c2.7 0 5 1.72 5 4 0 1.3-.76 2.46-2.05 3.24L13 13.2V14z"
                                                        />
                                                    </svg>
                                                </a>
                                            </p>
                                        </div>
                                        <div v-else>
                                            <p class="mb-0 py-1">
                                                <img
                                                    :src="getDefaultFavicon(host)"
                                                    :alt="host"
                                                    width="15"
                                                    height="15"
                                                    class="mr-1"
                                                />
                                                <a
                                                    :href="'http://' + host"
                                                    class="text-decoration-none text-primary"
                                                    target="_blank"
                                                >
                                                    {{ host }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-muted"
                                            >{{ suffixedNumber(data.monthlyViews) }} {{ trans.views }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-else class="py-2 font-italic">
                            {{ trans.waiting_until_more_data }}
                        </p>
                    </div>

                    <div class="col-md-6 mt-4">
                        <h5 class="text-muted small text-uppercase font-weight-bold pb-2">
                            {{ trans.popular_reading_times }}
                        </h5>

                        <div v-if="Object.keys(data.popularReadingTimes).length > 0">
                            <div
                                :key="`${time}-${percentage}`"
                                v-for="(percentage, time, index) in data.popularReadingTimes"
                            >
                                <div
                                    class="d-flex py-2 align-items-center px-2"
                                    :class="index % 2 === 0 ? 'bg-list-muted' : ''"
                                >
                                    <div class="mr-auto">
                                        <p class="mb-0 py-1">
                                            {{ time }}
                                        </p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="text-muted">{{ `${percentage}%` }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-else class="py-2 font-italic">
                            {{ trans.waiting_until_more_data }}
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </section>
</template>

<script>
import { mapGetters, mapState } from 'vuex';
import LineChart from '../components/LineChart';
import NProgress from 'nprogress';
import PageHeader from '../components/PageHeader';
import Tooltip from '../directives/Tooltip';
import strings from '../mixins/strings';

export default {
    name: 'post-stats',

    components: {
        LineChart,
        PageHeader,
    },

    directives: {
        Tooltip,
    },

    mixins: [strings],

    data() {
        return {
            id: this.$route.params.id,
            data: null,
            isReady: false,
        };
    },

    computed: {
        ...mapState(['settings']),
        ...mapGetters({
            trans: 'settings/trans',
        }),

        canEditPost() {
            return this.settings.user.id == this.data.post.user_id;
        },

        viewsAreTrendingUp() {
            return this.data.monthOverMonthViews.direction === 'up';
        },

        visitsAreTrendingUp() {
            return this.data.monthOverMonthVisits.direction === 'up';
        },

        plotViewPoints() {
            return JSON.parse(this.data.graph.views);
        },

        plotVisitPoints() {
            return JSON.parse(this.data.graph.visits);
        },
    },

    async created() {
        await Promise.all([this.fetchStats()]);
        this.isReady = true;
        NProgress.done();
    },

    methods: {
        fetchStats() {
            return this.request()
                .get(`/api/posts/${this.id}/stats`)
                .then(({ data }) => {
                    this.data = data;
                    NProgress.inc();
                })
                .catch(() => {
                    this.$router.push({ name: 'stats' });
                    NProgress.done();
                });
        },

        getDefaultFavicon(host) {
            return `https://favicons.githubusercontent.com/${host}`;
        },
    },
};
</script>
