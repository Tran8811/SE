const express = require("express");
const http = require("http");
const socketIo = require("socket.io");
const mysql = require("mysql2");

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
  cors: {
    origin: "http://localhost:8080",  // Đổi thành domain của bạn nếu cần
    methods: ["GET", "POST"]
  }
});

// Kết nối MySQL
const db = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "chipchip1703",
  database: "cupid_db"
});

db.connect(err => {
  if (err) {
    console.error("❌ Lỗi kết nối MySQL:", err);
    return;
  }
  console.log("✅ Kết nối MySQL thành công!");
});

// Danh sách người dùng online
let users = {};

// Khi có người dùng kết nối
io.on("connection", (socket) => {
  console.log("🔗 Người dùng kết nối:", socket.id);

  // Khi user online
  socket.on("user_online", (userId) => {
    users[userId] = socket.id;
    console.log("🟢 Người dùng online:", users);
  });

  // Xử lý gửi tin nhắn
  socket.on("send_message", ({ senderId, receiverId, message }) => {
    console.log(`📩 ${senderId} gửi tin nhắn đến ${receiverId}: ${message}`);

    // Lưu vào database
    db.query(
      "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)",
      [senderId, receiverId, message]
    );

    // Nếu người nhận đang online, gửi tin nhắn real-time
    if (users[receiverId]) {
      io.to(users[receiverId]).emit("receive_message", { senderId, message });
    }
  });

  // Khi user disconnect
  socket.on("disconnect", () => {
    console.log("❌ Người dùng ngắt kết nối:", socket.id);
    for (let userId in users) {
      if (users[userId] === socket.id) {
        delete users[userId];
        break;
      }
    }
  });
});

// Chạy server trên cổng 3000
server.listen(3000, () => {
  console.log("🚀 Server chat đang chạy tại http://localhost:3000");
});
