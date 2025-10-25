import { useState, useEffect, useRef } from 'react';
import { io } from 'socket.io-client';
import './App.css';

const socket = io('http://localhost:3000');

function App() {
  const [username, setUsername] = useState('');
  const [isJoined, setIsJoined] = useState(false);
  const [messages, setMessages] = useState([]);
  const [inputMessage, setInputMessage] = useState('');
  const [onlineUsers, setOnlineUsers] = useState([]);
  const [typingUsers, setTypingUsers] = useState(new Set());
  const messagesEndRef = useRef(null);
  const typingTimeoutRef = useRef(null);

  useEffect(() => {
    // 监听用户加入
    socket.on('user-joined', (data) => {
      setMessages((prev) => [
        ...prev,
        {
          id: Date.now(),
          type: 'system',
          message: `${data.username} 加入了聊天室`,
          timestamp: new Date().toISOString(),
        },
      ]);
    });

    // 监听用户离开
    socket.on('user-left', (data) => {
      setMessages((prev) => [
        ...prev,
        {
          id: Date.now(),
          type: 'system',
          message: `${data.username} 离开了聊天室`,
          timestamp: new Date().toISOString(),
        },
      ]);
    });

    // 监听聊天消息
    socket.on('chat-message', (message) => {
      setMessages((prev) => [...prev, message]);
    });

    // 监听在线用户列表
    socket.on('users-list', (users) => {
      setOnlineUsers(users);
    });

    // 监听用户正在输入
    socket.on('user-typing', ({ username, isTyping }) => {
      setTypingUsers((prev) => {
        const newSet = new Set(prev);
        if (isTyping) {
          newSet.add(username);
        } else {
          newSet.delete(username);
        }
        return newSet;
      });
    });

    return () => {
      socket.off('user-joined');
      socket.off('user-left');
      socket.off('chat-message');
      socket.off('users-list');
      socket.off('user-typing');
    };
  }, []);

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  const handleJoin = (e) => {
    e.preventDefault();
    if (username.trim()) {
      socket.emit('join', username);
      setIsJoined(true);
    }
  };

  const handleSendMessage = (e) => {
    e.preventDefault();
    if (inputMessage.trim()) {
      socket.emit('chat-message', { message: inputMessage });
      setInputMessage('');
      socket.emit('typing', false);
    }
  };

  const handleTyping = (e) => {
    setInputMessage(e.target.value);

    // 清除之前的定时器
    if (typingTimeoutRef.current) {
      clearTimeout(typingTimeoutRef.current);
    }

    // 发送正在输入事件
    socket.emit('typing', true);

    // 1.5秒后发送停止输入事件
    typingTimeoutRef.current = setTimeout(() => {
      socket.emit('typing', false);
    }, 1500);
  };

  const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('zh-CN', {
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  if (!isJoined) {
    return (
      <div className="min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center p-4">
        <div className="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
          <h1 className="text-4xl font-bold text-center mb-2 text-gray-800">
            43Chat
          </h1>
          <p className="text-center text-gray-600 mb-8">实时聊天应用</p>
          <form onSubmit={handleJoin} className="space-y-4">
            <div>
              <label
                htmlFor="username"
                className="block text-sm font-medium text-gray-700 mb-2"
              >
                输入你的昵称
              </label>
              <input
                type="text"
                id="username"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                placeholder="请输入昵称..."
                maxLength={20}
                required
              />
            </div>
            <button
              type="submit"
              className="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105"
            >
              加入聊天室
            </button>
          </form>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100">
      <div className="container mx-auto h-screen flex flex-col p-4">
        {/* Header */}
        <div className="bg-white rounded-t-lg shadow-md p-4 flex justify-between items-center">
          <div>
            <h1 className="text-2xl font-bold text-gray-800">43Chat</h1>
            <p className="text-sm text-gray-600">欢迎, {username}</p>
          </div>
          <div className="text-right">
            <p className="text-sm text-gray-600">在线用户</p>
            <p className="text-xl font-semibold text-blue-500">
              {onlineUsers.length}
            </p>
          </div>
        </div>

        {/* Main Chat Area */}
        <div className="flex-1 flex bg-white shadow-md overflow-hidden">
          {/* Messages Area */}
          <div className="flex-1 flex flex-col">
            {/* Messages */}
            <div className="flex-1 overflow-y-auto p-4 space-y-3">
              {messages.map((msg) => {
                if (msg.type === 'system') {
                  return (
                    <div
                      key={msg.id}
                      className="text-center text-gray-500 text-sm py-2"
                    >
                      {msg.message}
                    </div>
                  );
                }

                const isOwnMessage = msg.username === username;

                return (
                  <div
                    key={msg.id}
                    className={`flex ${
                      isOwnMessage ? 'justify-end' : 'justify-start'
                    }`}
                  >
                    <div
                      className={`max-w-xs lg:max-w-md xl:max-w-lg ${
                        isOwnMessage ? 'order-2' : 'order-1'
                      }`}
                    >
                      <div className="flex items-baseline space-x-2 mb-1">
                        <span
                          className={`text-xs font-semibold ${
                            isOwnMessage ? 'text-blue-600' : 'text-gray-600'
                          }`}
                        >
                          {msg.username}
                        </span>
                        <span className="text-xs text-gray-400">
                          {formatTime(msg.timestamp)}
                        </span>
                      </div>
                      <div
                        className={`rounded-lg px-4 py-2 ${
                          isOwnMessage
                            ? 'bg-blue-500 text-white'
                            : 'bg-gray-200 text-gray-800'
                        }`}
                      >
                        {msg.message}
                      </div>
                    </div>
                  </div>
                );
              })}
              <div ref={messagesEndRef} />
            </div>

            {/* Typing Indicator */}
            {typingUsers.size > 0 && (
              <div className="px-4 py-2 text-sm text-gray-500 italic">
                {Array.from(typingUsers).join(', ')} 正在输入...
              </div>
            )}

            {/* Message Input */}
            <div className="border-t border-gray-200 p-4">
              <form onSubmit={handleSendMessage} className="flex space-x-2">
                <input
                  type="text"
                  value={inputMessage}
                  onChange={handleTyping}
                  className="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                  placeholder="输入消息..."
                  maxLength={500}
                />
                <button
                  type="submit"
                  className="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200"
                >
                  发送
                </button>
              </form>
            </div>
          </div>

          {/* Online Users Sidebar */}
          <div className="w-64 border-l border-gray-200 bg-gray-50 p-4 overflow-y-auto hidden md:block">
            <h2 className="text-lg font-semibold text-gray-800 mb-4">
              在线用户 ({onlineUsers.length})
            </h2>
            <div className="space-y-2">
              {onlineUsers.map((user) => (
                <div
                  key={user.userId}
                  className="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition"
                >
                  <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                  <span
                    className={`text-sm ${
                      user.username === username
                        ? 'font-semibold text-blue-600'
                        : 'text-gray-700'
                    }`}
                  >
                    {user.username}
                    {user.username === username && ' (你)'}
                  </span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;
