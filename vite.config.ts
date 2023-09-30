import { svelte } from "@sveltejs/vite-plugin-svelte";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/index.ts"],
        }),
        svelte(),
    ],
});
