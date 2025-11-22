import { defineConfig } from "vite";
import { resolve } from "path";

export default defineConfig({
  root: ".", // 👈 raiz do projeto
  publicDir: "public", // 👈 mantém sua pasta public original
  server: {
    port: 5173,
    strictPort: true,
  },
  build: {
    outDir: "public/assets", // 👈 saída organizada
    emptyOutDir: false, // não apagar a pasta public inteira
    manifest: true, // 👈 gera manifest.json para produção
    rollupOptions: {
      input: {
        app: resolve(__dirname, "app/resources/js/app.js"), // caminho absoluto correto
      },
      output: {
        assetFileNames: "css/[name]-[hash][extname]",
        chunkFileNames: "js/[name]-[hash].js",
        entryFileNames: "js/[name]-[hash].js",
      },
    },
  },
});
