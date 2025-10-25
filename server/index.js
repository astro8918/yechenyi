import express from 'express';
import { createServer } from 'http';
import { Server } from 'socket.io';
import cors from 'cors';

const app = express();
const httpServer = createServer(app);
const io = new Server(httpServer, {
  cors: {
    origin: "http://localhost:5173", // Vite 默认端口
    methods: ["GET", "POST"]
  }
});

app.use(cors());
app.use(express.json());

// 存储在线用户
const users = new Map();

// 存储聊天历史记录（最多保存100条）
const messageHistory = [];

// Socket.io 连接处理
io.on('connection', (socket) => {
  console.log('用户连接:', socket.id);

  // 用户加入聊天室
  socket.on('join', (username) => {
    users.set(socket.id, username);
    console.log(`${username} 加入聊天室`);

    // 发送历史消息给新加入的用户
    if (messageHistory.length > 0) {
      socket.emit('message-history', messageHistory);
    }

    // 广播新用户加入
    io.emit('user-joined', {
      username,
      userId: socket.id,
      totalUsers: users.size
    });

    // 发送当前在线用户列表
    socket.emit('users-list', Array.from(users.entries()).map(([id, name]) => ({
      userId: id,
      username: name
    })));
  });

  // 接收聊天消息
  socket.on('chat-message', (data) => {
    const username = users.get(socket.id);
    const message = {
      id: Date.now(),
      username: username || '匿名',
      message: data.message,
      timestamp: new Date().toISOString()
    };

    // 保存消息到历史记录
    messageHistory.push(message);

    // 限制历史记录最多保存100条
    if (messageHistory.length > 100) {
      messageHistory.shift();
    }

    // 广播消息给所有客户端
    io.emit('chat-message', message);
  });

  // 用户正在输入
  socket.on('typing', (isTyping) => {
    const username = users.get(socket.id);
    socket.broadcast.emit('user-typing', {
      username,
      isTyping
    });
  });

  // 用户断开连接
  socket.on('disconnect', () => {
    const username = users.get(socket.id);
    users.delete(socket.id);
    console.log(`${username} 离开聊天室`);

    // 广播用户离开
    io.emit('user-left', {
      username,
      userId: socket.id,
      totalUsers: users.size
    });
  });
});

// 健康检查接口
app.get('/health', (req, res) => {
  res.json({
    status: 'ok',
    users: users.size,
    messages: messageHistory.length,
    timestamp: new Date().toISOString()
  });
});

// 获取聊天历史记录
app.get('/messages', (req, res) => {
  res.json({
    messages: messageHistory,
    total: messageHistory.length
  });
});

const PORT = process.env.PORT || 3000;

httpServer.listen(PORT, () => {
  console.log(`🚀 服务器运行在 http://localhost:${PORT}`);
  console.log(`📡 WebSocket 已就绪`);
});
