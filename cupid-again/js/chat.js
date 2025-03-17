document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("sendBtn").addEventListener("click", function () {
    let input = document.getElementById("message");
    let messageText = input.value.trim();

    if (messageText !== "") {
      let chatBox = document.querySelector(".chat-box");

      let newMessage = document.createElement("div");
      newMessage.classList.add("message", "sent");
      newMessage.innerText = messageText;

      chatBox.appendChild(newMessage);
      chatBox.scrollTop = chatBox.scrollHeight; // Cuộn xuống tin nhắn mới nhất
      input.value = ""; // Xóa input sau khi gửi
    }
  });
});
