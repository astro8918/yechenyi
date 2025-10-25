# 43Chat - 实时聊天应用

一个基于 WebSocket 技术的简单实时聊天应用，使用 Node.js、Express、Socket.io、React 和 Tailwind CSS 构建。

## 项目结构

```
43Chat/
├── server/          # Node.js 后端服务
│   ├── index.js     # Express + Socket.io 服务器
│   └── package.json
└── client/          # React 前端应用
    ├── src/
    │   ├── App.jsx  # 主要聊天界面组件
    │   ├── App.css
    │   └── index.css
    └── package.json
```

## 技术栈

### 服务端
- **Node.js** - JavaScript 运行时
- **Express** - Web 应用框架
- **Socket.io** - 实时双向通信库
- **CORS** - 跨域资源共享

### 客户端
- **React** - UI 框架
- **Vite** - 构建工具
- **Socket.io-client** - WebSocket 客户端
- **Tailwind CSS** - CSS 框架

## 功能特性

- ✅ 实时消息发送和接收
- ✅ 用户加入/离开通知
- ✅ 在线用户列表显示
- ✅ 正在输入提示
- ✅ 消息时间戳
- ✅ 响应式设计
- ✅ 优雅的用户界面

## 快速开始

### 前置要求

确保你的系统已安装：
- Node.js (v16 或更高版本)
- npm 或 yarn

### 安装和运行

#### 1. 启动服务端

```bash
# 进入服务端目录
cd server

# 安装依赖（如果还没安装）
npm install

# 启动服务器
npm start

# 或使用开发模式（自动重启）
npm run dev
```

服务器将在 `http://localhost:3000` 启动

#### 2. 启动客户端

打开新的终端窗口：

```bash
# 进入客户端目录
cd client

# 安装依赖（如果还没安装）
npm install

# 启动开发服务器
npm run dev
```

客户端将在 `http://localhost:5173` 启动

#### 3. 开始聊天

1. 在浏览器中打开 `http://localhost:5173`
2. 输入你的昵称
3. 点击"加入聊天室"
4. 开始发送消息！

提示：你可以打开多个浏览器标签页或窗口来模拟多用户聊天。

## API 接口

### WebSocket 事件

#### 客户端发送的事件：
- `join` - 用户加入聊天室
  ```js
  socket.emit('join', username)
  ```
- `chat-message` - 发送聊天消息
  ```js
  socket.emit('chat-message', { message: 'Hello' })
  ```
- `typing` - 用户正在输入
  ```js
  socket.emit('typing', true/false)
  ```

#### 服务端发送的事件：
- `user-joined` - 新用户加入
- `user-left` - 用户离开
- `chat-message` - 接收聊天消息
- `users-list` - 在线用户列表
- `user-typing` - 其他用户正在输入

### HTTP 接口

- `GET /health` - 健康检查接口
  ```json
  {
    "status": "ok",
    "users": 5,
    "timestamp": "2025-10-25T03:30:00.000Z"
  }
  ```

## 开发

### 服务端开发

```bash
cd server
npm run dev  # 使用 --watch 模式自动重启
```

### 客户端开发

```bash
cd client
npm run dev  # Vite 热更新
```

### 构建生产版本

```bash
cd client
npm run build
```

## 配置

### 修改端口

**服务端端口** (默认 3000):
编辑 `server/index.js`:
```js
const PORT = process.env.PORT || 3000;
```

**客户端端口** (默认 5173):
Vite 默认使用 5173 端口，可以在 `vite.config.js` 中修改

**WebSocket 连接地址**:
编辑 `client/src/App.jsx`:
```js
const socket = io('http://localhost:3000');
```

## 故障排除

### WebSocket 连接失败
- 确保服务端正在运行
- 检查防火墙设置
- 确认端口没有被占用

### Tailwind 样式不生效
- 确保已正确安装 `tailwindcss`, `postcss`, `autoprefixer`
- 检查 `tailwind.config.js` 配置
- 重启开发服务器

## 下一步改进建议

- [ ] 添加用户认证
- [ ] 消息持久化（数据库）
- [ ] 私聊功能
- [ ] 文件/图片分享
- [ ] 表情符号支持
- [ ] 消息搜索
- [ ] 聊天室/频道功能
- [ ] 消息已读状态
- [ ] 用户头像
- [ ] 深色模式

## 许可证

MIT

## 作者

创建于 2025 年
