// Mở cửa sổ chat
function openChat() {
  document.getElementById("chatWindow").style.bottom = "20px";
}

// Đóng cửa sổ chat
function closeChat() {
  document.getElementById("chatWindow").style.bottom = "-100%";
}

document.addEventListener("DOMContentLoaded", function () {
  const mbtiResult = localStorage.getItem("mbtiResult");
  if (mbtiResult) {
    document.getElementById("mbti-display").innerText = mbtiResult;
  } else {
    document.getElementById("mbti-display").innerText = "Chưa có kết quả";
  }
});
