document.addEventListener("DOMContentLoaded", () => {
  const receiverId = document.getElementById("chatWith")?.dataset.id;
  const messageInput = document.getElementById("messageInput");
  const sendBtn = document.getElementById("sendBtn");
  const imageInput = document.getElementById("imageInput");
  const overlay = document.getElementById("overlay");
  const largeImage = document.getElementById("largeImage");
  const chatBox = document.getElementById("chatBox");
  const imagePreviewWrapper = document.getElementById("imagePreviewContainer");
  const imagePreview = document.getElementById("imagePreview");
  const cancelImageBtn = document.querySelector(".cancel-image-btn");

  console.log("👤 ID người đang chat:", receiverId);

  let previousMessages = "";

  if (!receiverId || !messageInput || !sendBtn) {
    if (messageInput) messageInput.disabled = true;
    if (sendBtn) sendBtn.disabled = true;
    return;
  }

  const addImageClickEvents = () => {
    document.querySelectorAll(".chat-image").forEach(img => {
      img.onclick = () => {
        largeImage.src = img.src;
        overlay.style.display = "flex";
      };
    });
  };

  const loadMessages = (forceScroll = false) => {
    fetch("get-messages.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `incoming_id=${receiverId}`
    })
      .then(res => res.text())
      .then(html => {
        if (html !== previousMessages) {
          chatBox.innerHTML = html;
          if (forceScroll) chatBox.scrollTop = chatBox.scrollHeight;
          previousMessages = html;
          addImageClickEvents();
        }
      })
      .catch(err => console.error("🔴 Lỗi tải tin nhắn:", err));
  };

  const sendMessage = () => {
    const message = messageInput.value.trim();
    const imageFile = imageInput.files[0];

    if (!message && !imageFile) return;

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
        imageInput.value = "";
        imagePreviewWrapper.style.display = "none";
        loadMessages(true);
      })
      .catch(err => console.error("🔴 Lỗi gửi tin nhắn:", err));
  };

  // Bật lại input
  messageInput.disabled = sendBtn.disabled = false;
  loadMessages(true);

  // Sự kiện
  imageInput.onchange = () => {
    const file = imageInput.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => {
        imagePreview.src = e.target.result;
        imagePreviewWrapper.style.display = "inline-block";
      };
      reader.readAsDataURL(file);
    } else {
      imagePreviewWrapper.style.display = "none";
    }
  };

  cancelImageBtn.onclick = () => {
    imageInput.value = "";
    imagePreview.src = "";
    imagePreviewWrapper.style.display = "none";
  };

  overlay.onclick = () => overlay.style.display = "none";
  messageInput.addEventListener("keypress", e => {
    if (e.key === "Enter") sendMessage();
  });
  sendBtn.onclick = sendMessage;
  setInterval(() => loadMessages(false), 3000);
});
