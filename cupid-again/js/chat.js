const socket = io("http://localhost:3000"); // Kết nối đến server socket.io

document.addEventListener("DOMContentLoaded", function () {
  const chatWithSelect = document.getElementById("chatWith");
  const messageInput = document.getElementById("message");
  const sendBtn = document.getElementById("sendBtn");
  const chatBox = document.querySelector(".chat-box");

  // Lấy user ID từ sessionStorage (được lưu sau khi đăng nhập)
  const currentUserId = sessionStorage.getItem("user_id");

  // Khi chọn người để chat
  chatWithSelect.addEventListener("change", function () {
    if (chatWithSelect.value !== "") {
      messageInput.removeAttribute("disabled");
      sendBtn.removeAttribute("disabled");

      // Lưu ID người được chọn vào session
      sessionStorage.setItem("chat_with", chatWithSelect.value);
    } else {
      messageInput.setAttribute("disabled", "true");
      sendBtn.setAttribute("disabled", "true");
    }
  });

  // Gửi tin nhắn
  sendBtn.addEventListener("click", function () {
    let messageText = messageInput.value.trim();
    let receiverId = sessionStorage.getItem("chat_with"); // Lấy ID người nhận

    if (messageText !== "" && receiverId) {
      // Hiển thị tin nhắn trên giao diện
      let newMessage = document.createElement("div");
      newMessage.classList.add("message", "sent");
      newMessage.innerText = messageText;
      chatBox.appendChild(newMessage);
      chatBox.scrollTop = chatBox.scrollHeight; // Cuộn xuống cuối

      // Gửi tin nhắn qua Socket.io đến server
      socket.emit("send_message", {
        senderId: currentUserId,
        receiverId: receiverId,
        message: messageText
      });

      messageInput.value = ""; // Xóa input sau khi gửi
    }
  });

  // Nhận tin nhắn từ server
  socket.on("receive_message", ({ senderId, message }) => {
    let newMessage = document.createElement("div");
    newMessage.classList.add("message", "received");
    newMessage.innerText = message;
    chatBox.appendChild(newMessage);
    chatBox.scrollTop = chatBox.scrollHeight; // Cuộn xuống cuối
  });

  // Load danh sách người dùng để chọn
  fetch("../php/get_users.php")
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        data.users.forEach(user => {
          let option = document.createElement("option");
          option.value = user.id;
          option.textContent = user.username;
          chatWithSelect.appendChild(option);
        });
      } else {
        console.error("Lỗi lấy danh sách người dùng: ", data.message);
      }
    })
    .catch(error => console.error("Lỗi API:", error));
});
