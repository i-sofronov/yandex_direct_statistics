import '../scss/app.scss';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name) => {
        const pages = import.meta.glob('./features/**/pages/*.vue', { eager: true });

        const path = name.split('/');
        const feature = path[0];
        const pageName = path.slice(1).join('/');

        const page = pages[`./features/${feature}/pages/${pageName}.vue`];

        if (page) {
            return page.default;
        }

        throw new Error(`Page not found: ${pageName}`);
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#151515'
    }
});
