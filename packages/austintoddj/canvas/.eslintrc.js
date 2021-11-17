module.exports = {
    root: true,
    env: {
        node: true,
        browser: true,
        es6: true,
    },
    extends: ['plugin:vue/essential', 'eslint:recommended', 'prettier/vue', 'plugin:prettier/recommended'],
    rules: {
        curly: 'error',
        'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'no-mixed-operators': 'error',
        'no-param-reassign': 'error',
        'sort-imports-es6-autofix/sort-imports-es6': [
            2,
            {
                ignoreCase: false,
                ignoreMemberSort: false,
                memberSyntaxSortOrder: ['none', 'all', 'multiple', 'single'],
            },
        ],
        'vue/attribute-hyphenation': ['error', 'always'],
        'vue/attributes-order': [
            2,
            {
                order: [
                    'GLOBAL', // id
                    'DEFINITION', // is
                    'UNIQUE', // ref, key, slot
                    'CONDITIONALS', // v-if, v-else-if, v-else, v-show, v-cloak
                    'LIST_RENDERING', // v-for
                    'OTHER_ATTR', // all unspecified bound & unbound attributes
                    'BINDING', // v-model
                    'RENDER_MODIFIERS', // v-pre, v-once
                    'CONTENT', // v-html, v-text
                    'EVENTS', // v-on
                ],
            },
        ],
        'vue/component-name-in-template-casing': ['error', 'kebab-case'],
        'vue/html-end-tags': 'error',
        'vue/html-self-closing': [
            'error',
            {
                html: {
                    void: 'always',
                    normal: 'always',
                    component: 'always',
                },
                svg: 'always',
                math: 'always',
            },
        ],
        'vue/name-property-casing': ['error', 'kebab-case'],
        'vue/no-multi-spaces': 'error',
        'vue/order-in-components': [
            'error',
            {
                order: [
                    'el',
                    'name',
                    'parent',
                    'functional',
                    ['delimiters', 'comments'],
                    ['components', 'directives', 'filters'],
                    'extends',
                    'mixins',
                    'inheritAttrs',
                    'model',
                    ['props', 'propsData'],
                    'data',
                    'computed',
                    'watch',
                    'LIFECYCLE_HOOKS',
                    'methods',
                    ['template', 'render'],
                    'renderError',
                ],
            },
        ],
        'vue/require-prop-types': ['error'],
        'vue/require-valid-default-prop': ['error'],
    },
    globals: {},
    parserOptions: {
        ecmaVersion: 2018,
        parser: 'babel-eslint',
    },
    plugins: ['vue', 'sort-imports-es6-autofix'],
};
