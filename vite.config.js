import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: "assets/app-DwV9ED5I.js", // Force a fixed name
                chunkFileNames: "assets/[name]-[hash].js", // Ensure no conflicts
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith(".css")) {
                        return "assets/app-CTjxDtVZ.css";
                    }
                    return "assets/[name]-[hash].[ext]";
                },
            },
        },
    },
});
