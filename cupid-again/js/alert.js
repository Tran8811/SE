function showCustomAlert(message) {
  // Nếu alert đã tồn tại, xóa nó trước
  const existingAlert = document.getElementById("custom-alert");
  if (existingAlert) {
    existingAlert.remove();
  }

  // Tạo div chính của alert
  const alertBox = document.createElement("div");
  alertBox.id = "custom-alert";
  alertBox.innerText = message;

  // Thêm vào body
  document.body.appendChild(alertBox);

  // Hiển thị với hiệu ứng fade-in
  setTimeout(() => {
    alertBox.style.opacity = "1";
  }, 10);

  // Tự động ẩn sau 3 giây
  setTimeout(() => {
    alertBox.style.opacity = "0";
    setTimeout(() => alertBox.remove(), 500);
  }, 3000);
}

// Thêm CSS vào trang
const style = document.createElement("style");
style.innerHTML = `
    #custom-alert {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #FFD1DC; /* Pastel hồng */
        color: #5A5A5A; /* Màu chữ */
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 16px;
        font-weight: bold;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        z-index: 9999;
    }
`;
document.head.appendChild(style);
