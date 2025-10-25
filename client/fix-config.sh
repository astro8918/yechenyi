#!/bin/bash

echo "🔧 修复 Tailwind CSS 配置文件..."

# 修复 postcss.config.js
cat > postcss.config.js << 'EOF'
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOF

# 修复 tailwind.config.js
cat > tailwind.config.js << 'EOF'
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
EOF

echo "✅ 配置文件已修复！"
echo "📝 已更新:"
echo "   - postcss.config.js"
echo "   - tailwind.config.js"
echo ""
echo "🚀 现在运行: npm run dev"
