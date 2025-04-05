const io = require("socket.io-client");

// Kết nối tới server
const socket = io("http://localhost:3000");

const readline = require("readline"); // Dùng để nhập từ bàn phím
const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

let currentUserId;

// Hàm đăng nhập người dùng
function login() {
  rl.question("Nhập user ID của bạn: ", (userId) => {
    currentUserId = userId;
    socket.emit("user_online", userId);
    console.log(`✅ Bạn đã online với ID: ${userId}`);
    chat();
  });
}

// Hàm chat
function chat() {
  rl.question("Nhập ID người nhận: ", (receiverId) => {
    rl.question("Nhập tin nhắn: ", (message) => {
      socket.emit("send_message", {
        senderId: currentUserId,
        receiverId: receiverId,
        message: message
      });
      console.log(`📩 Bạn đã gửi: "${message}" đến ${receiverId}`);
      chat(); // Lặp lại để tiếp tục gửi tin nhắn
    });
  });
}

// Lắng nghe tin nhắn từ server
socket.on("receive_message", ({ senderId, message }) => {
  console.log(`📨 Tin nhắn từ ${senderId}: ${message}`);
});

// Khi kết nối thành công, bắt đầu login
socket.on("connect", () => {
  console.log("🔗 Đã kết nối đến server!");
  login();
});
