document.addEventListener("DOMContentLoaded", function () {
   const chatWith = document.getElementById("chatWith"),
    messageInput = document.getElementById("message"),
    sendBtn = document.getElementById("sendBtn"),
    chatBox = document.getElementById("chatBox");
    let currentUser = document.getElementById("user-name");
  fetch("/cupid-again/php/get_user_profile.php")
    .then(response => response.json())
    .then(data => {
      console.log("Dữ liệu từ server:", data); // ✅ Debug JSON trả về

      if (data.status === "success" && data.users) {  // Kiểm tra "users"
        currentUser.textContent = data.users.username || "Unknown";
      } else {
        console.error("🔴 Lỗi: Không tìm thấy users trong dữ liệu API!");
      }
    })
    .catch(error => console.error("🔴 Lỗi tải user:", error));

  chatWith.addEventListener("change", function () {
    receiverId = this.value;
    if (receiverId) {
      messageInput.disabled = false;
      sendBtn.disabled = false;
      chatBox.innerHTML = "";
      loadMessages();
    } else {
      messageInput.disabled = true;
      sendBtn.disabled = true;
      chatBox.innerHTML = "";
    }
  });

  sendBtn.addEventListener("click", function () {
    sendMessage();
  });

  messageInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      sendMessage();
    }
  });

  const chatWithSelect = document.getElementById("chatWith");
  let receiverId = ""; // Biến lưu người nhận tin nhắn

  chatWithSelect.addEventListener("change", () => {
    receiverId = chatWithSelect.value;
    console.log("🟢 Đã chọn người nhận ID:", receiverId);

    if (receiverId) {
      document.getElementById("message").disabled = false;
      document.getElementById("sendBtn").disabled = false;
      loadMessages();
    } else {
      document.getElementById("message").disabled = true;
      document.getElementById("sendBtn").disabled = true;
    }
  });

  function sendMessage() {
    let message = document.getElementById("message").value.trim();

    if (!receiverId || !message) {
      console.log("❌ Thiếu thông tin gửi tin nhắn!");
      return;
    }

    console.log("🟢 Đang gửi tin nhắn tới:", receiverId, "Nội dung:", message);

    fetch("send-messages.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `incoming_id=${receiverId}&message=${encodeURIComponent(message)}`
    })
      .then(response => response.text())
      .then(data => {
        console.log("✅ Tin nhắn đã gửi:", data);
        document.getElementById("message").value = "";
        loadMessages(); // Cập nhật chat box
      })
      .catch(error => console.error("🔴 Lỗi gửi tin nhắn:", error));
  }

  document.getElementById("sendBtn").addEventListener("click", sendMessage);

  function loadMessages() {
    if (!receiverId) {
      console.log("❌ Không có receiverId để tải tin nhắn!");
      return;
    }

    console.log("🟢 Đang tải tin nhắn của:", receiverId);

    fetch("get-messages.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `incoming_id=${receiverId}`
    })
      .then(response => response.text())
      .then(html => {
        chatBox.innerHTML = html;
        chatBox.scrollTop = chatBox.scrollHeight;
      })
      .catch(error => console.error("🔴 Lỗi tải tin nhắn:", error));
  }


  setInterval(loadMessages, 3000);
});
