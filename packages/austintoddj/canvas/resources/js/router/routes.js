import AllStats from '../views/AllStats';
import EditPost from '../views/EditPost';
import EditSettings from '../views/EditSettings';
import EditTag from '../views/EditTag';
import EditTopic from '../views/EditTopic';
import EditUser from '../views/EditUser';
import PostList from '../views/PostList';
import PostStats from '../views/PostStats';
import TagList from '../views/TagList';
import TopicList from '../views/TopicList';
import UserList from '../views/UserList';
import settings from '../store/modules/settings';

let isAdmin = settings.state.user.role === 3;

export default [
    {
        path: '/',
        name: 'home',
        redirect: '/stats',
    },
    {
        path: '/stats',
        name: 'stats',
        component: AllStats,
    },
    {
        path: '/posts',
        name: 'posts',
        component: PostList,
    },
    {
        path: '/posts/create',
        name: 'create-post',
        component: EditPost,
    },
    {
        path: '/posts/:id/stats',
        name: 'post-stats',
        component: PostStats,
    },
    {
        path: '/posts/:id/edit',
        name: 'edit-post',
        component: EditPost,
    },
    {
        path: '/tags',
        name: 'tags',
        component: TagList,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/tags/create',
        name: 'create-tag',
        component: EditTag,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/tags/:id/edit',
        name: 'edit-tag',
        component: EditTag,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/topics',
        name: 'topics',
        component: TopicList,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/topics/create',
        name: 'create-topic',
        component: EditTopic,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/topics/:id/edit',
        name: 'edit-topic',
        component: EditTopic,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/settings',
        name: 'edit-settings',
        component: EditSettings,
    },
    {
        path: '/users',
        name: 'users',
        component: UserList,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/users/create',
        name: 'create-user',
        component: EditUser,
        beforeEnter: (to, from, next) => {
            isAdmin ? next() : next({ name: 'home' });
        },
    },
    {
        path: '/users/:id/edit',
        name: 'edit-user',
        component: EditUser,
        beforeEnter: (to, from, next) => {
            if (isAdmin || settings.state.user.id == to.params.id) {
                next();
            } else {
                next({ name: 'home' });
            }
        },
    },
    {
        path: '*',
        name: 'catch-all',
        redirect: '/stats',
    },
];
