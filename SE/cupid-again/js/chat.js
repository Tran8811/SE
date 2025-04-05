document.addEventListener("DOMContentLoaded", function () {
  const receiverId = document.getElementById("chatWith")?.dataset.id;
  const messageInput = document.getElementById("message");
  const sendBtn = document.getElementById("sendBtn");

  if (!receiverId) {
    messageInput.disabled = true;
    sendBtn.disabled = true;
    return;
  }

  messageInput.disabled = false;
  sendBtn.disabled = false;
  loadMessages();

  sendBtn.addEventListener("click", sendMessage);
  messageInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") sendMessage();
  });

  function sendMessage() {
    const message = messageInput.value.trim();
    const imageFile = document.getElementById("imageInput").files[0];

    if (!message && !imageFile) {
      console.log("❌ Thiếu nội dung hoặc ảnh để gửi!");
      return;
    }

    const formData = new FormData();
    formData.append("incoming_id", receiverId);
    if (message) formData.append("message", message);
    if (imageFile) formData.append("image", imageFile);

    fetch("send-messages.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.text())
      .then(data => {
        console.log("✅ Tin nhắn đã gửi:", data);
        messageInput.value = "";
        document.getElementById("imageInput").value = "";
        loadMessages();
      })
      .catch(err => console.error("🔴 Lỗi gửi tin nhắn:", err));
  }

  function loadMessages() {
    fetch("get-messages.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `incoming_id=${receiverId}`
    })
      .then(res => res.text())
      .then(html => {
        const chatBox = document.getElementById("chatBox");
        chatBox.innerHTML = html;
        chatBox.scrollTop = chatBox.scrollHeight;
      })
      .catch(err => console.error("🔴 Lỗi tải tin nhắn:", err));
  }

  setInterval(loadMessages, 3000);
});
