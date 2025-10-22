import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0", // Allow external access
        port: 5173,
        hmr: {
            host: "localhost", // We'll override this
        },
        cors: true, // Enable CORS
    },
});
