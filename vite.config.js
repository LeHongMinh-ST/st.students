import fs from "fs";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

const isDocker = process.env.DOCKER === "true";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.scss",
                "resources/css/auth.scss",
                "resources/js/app.js",
                "resources/js/auth/login.js",
            ],
            refresh: true,
        }),
    ],
    esbuild: {
        jsx: "automatic",
    },
    server: {
        host: "0.0.0.0",
        watch: {
            usePolling: true,
        },
        port: 5174,
        ...(isDocker
            ? {
                  https: {
                      key: fs.readFileSync(
                          "/etc/nginx/certs/st.students.dev-key.pem",
                      ),
                      cert: fs.readFileSync(
                          "/etc/nginx/certs/st.students.dev.pem",
                      ),
                  },
                  hmr: {
                      protocol: "wss",
                      host: "st.students.dev",
                      port: 5174,
                  },
              }
            : {
                  https: false, // Không bật HTTPS khi chạy cục bộ
                  hmr: {
                      protocol: "ws", // Dùng WebSocket thường
                  },
              }),
        strictPort: true,
        cors: true,
    },
    preview: {
        https: isDocker,
    },
});
