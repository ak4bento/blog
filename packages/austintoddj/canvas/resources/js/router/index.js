import NProgress from 'nprogress';
import Router from 'vue-router';
import Vue from 'vue';
import routes from './routes';
import settings from '../store/modules/settings';

Vue.use(Router);

NProgress.configure({
    showSpinner: false,
    easing: 'ease',
    speed: 300,
});

const router = createRouter();

export default router;

function createRouter() {
    const router = new Router({
        base: settings.state.path,
        mode: 'history',
        routes,
    });

    router.beforeEach((to, from, next) => {
        NProgress.start();
        next();
    });

    return router;
}
