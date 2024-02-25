import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import vuetify from "vite-plugin-vuetify";
import TurboConsole from 'unplugin-turbo-console/vite'
const host = "alnouran.test";
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/main.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({ autoImport: true }),
        TurboConsole({})
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    server: {
        host,
        hmr: { host },
    },
});
