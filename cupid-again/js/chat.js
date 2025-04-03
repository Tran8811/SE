document.addEventListener("DOMContentLoaded", function () {
  const chatWith = document.getElementById("chatWith"),
    messageInput = document.getElementById("message"),
    sendBtn = document.getElementById("sendBtn"),
    chatBox = document.getElementById("chatBox"),
    imageInput = document.getElementById("imageInput"),
    overlay = document.getElementById("overlay"),
    largeImage = document.getElementById("largeImage");

  let receiverId = "";

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

  sendBtn.addEventListener("click", sendMessage);
  messageInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") sendMessage();
  });

  function sendMessage() {
    let message = messageInput.value.trim();
    let imageFile = imageInput.files[0];

    if (!receiverId || (!message && !imageFile)) {
      console.log("❌ Thiếu nội dung hoặc ảnh để gửi!");
      return;
    }

    let formData = new FormData();
    formData.append("incoming_id", receiverId);
    if (message) formData.append("message", message);
    if (imageFile) formData.append("image", imageFile);

    fetch("send-messages.php", {
      method: "POST",
      body: formData
    })
      .then(response => response.text())
      .then(data => {
        console.log("✅ Tin nhắn đã gửi:", data);
        messageInput.value = "";
        imageInput.value = "";
        loadMessages();
      })
      .catch(error => console.error("🔴 Lỗi gửi tin nhắn:", error));
  }

  function loadMessages() {
    if (!receiverId) return;

    fetch("get-messages.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `incoming_id=${receiverId}`
    })
      .then(response => response.text())
      .then(html => {
        chatBox.innerHTML = html;
        chatBox.scrollTop = chatBox.scrollHeight;
        addImageClickEvents();
      })
      .catch(error => console.error("🔴 Lỗi tải tin nhắn:", error));
  }

  function addImageClickEvents() {
    document.querySelectorAll(".chat-image").forEach(img => {
      img.addEventListener("click", function () {
        const src = this.src;
        largeImage.src = src;  // Cập nhật ảnh phóng to
        overlay.style.display = "flex";  // Hiển thị overlay
      });
    });
  }


  document.getElementById("overlay").addEventListener("click", function () {
    overlay.style.display = "none";
  });

  setInterval(loadMessages, 3000);
});
