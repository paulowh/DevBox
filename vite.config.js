import { defineConfig } from "vite";
import { resolve } from "path";

export default defineConfig({
  root: ".",
  publicDir: false, // Desabilita cópia automática do public
  server: {
    port: 5173,
    strictPort: true,
    origin: "http://localhost:5173", // Importante para HMR
  },
  build: {
    outDir: "public/assets",
    emptyOutDir: true, // Limpa a pasta assets antes do build
    manifest: ".vite/manifest.json", // Caminho relativo dentro de outDir
    rollupOptions: {
      input: {
        app: resolve(__dirname, "app/Resources/js/app.js"),
      },
      output: {
        assetFileNames: "css/[name]-[hash][extname]",
        chunkFileNames: "js/[name]-[hash].js",
        entryFileNames: "js/[name]-[hash].js",
      },
    },
  },
});
