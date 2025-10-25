#!/bin/bash

echo "🧹 清理旧的依赖..."
rm -rf node_modules
rm -f package-lock.json

echo "📦 重新安装依赖..."
npm install

echo "✅ 完成！现在可以运行 npm run dev"
