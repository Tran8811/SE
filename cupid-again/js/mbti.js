const questions = [
  { text: "Khi ở trong một nhóm mới, bạn thường:", type: "E-I", options: ["E - Chủ động bắt chuyện với mọi người", "I - Chờ người khác bắt chuyện trước", "E - Quan sát một lúc rồi mới nói chuyện", "I - Cảm thấy không thoải mái và muốn rời đi"] },
  { text: "Khi phải đưa ra quyết định quan trọng, bạn sẽ:", type: "T-F", options: ["F - Dựa vào trực giác và cảm xúc của mình", "T - Dựa trên dữ liệu và phân tích logic", "F - Tham khảo ý kiến của người khác rồi cân nhắc", "T - Chờ đến phút cuối rồi quyết định theo cảm tính"] },
  { text: "Bạn thích môi trường làm việc nào hơn?", type: "E-I", options: ["E - Nhộn nhịp, có nhiều người tương tác", "I - Yên tĩnh, làm việc độc lập", "E - Có sự cân bằng giữa hai yếu tố trên", "I - Tùy thuộc vào tâm trạng từng ngày"] },
  { text: "Khi lên kế hoạch cho một chuyến đi, bạn sẽ:", type: "J-P", options: ["J - Lập kế hoạch chi tiết từ trước", "P - Chuẩn bị một số thứ cơ bản rồi để mọi thứ tự nhiên", "J - Đợi đến gần ngày đi mới chuẩn bị", "P - Không thích lên kế hoạch, cứ đi là được"] },
  { text: "Bạn cảm thấy thoải mái hơn khi:", type: "J-P", options: ["J - Tuân theo một lịch trình cố định", "P - Linh hoạt và làm mọi thứ theo cảm hứng", "J - Có lịch trình nhưng vẫn có thể thay đổi khi cần", "P - Không thích lập kế hoạch, thích sự ngẫu nhiên"] },
  { text: "Bạn thường tiếp thu thông tin theo cách nào?", type: "S-N", options: ["S - Qua trải nghiệm thực tế và cảm giác", "N - Phân tích chi tiết từng thông tin", "S - Nhớ qua hình ảnh và cảm xúc", "N - Tập trung vào ý nghĩa tổng thể hơn là chi tiết"] },
  { text: "Khi làm việc nhóm, bạn thường:", type: "E-I", options: ["E - Chủ động đưa ra ý tưởng và lãnh đạo", "I - Làm theo hướng dẫn và hoàn thành nhiệm vụ", "E - Đóng vai trò hỗ trợ và hòa giải", "I - Quan sát, suy nghĩ kỹ rồi mới góp ý"] },
  { text: "Bạn cảm thấy tràn đầy năng lượng hơn khi:", type: "E-I", options: ["E - Gặp gỡ và trò chuyện với nhiều người", "I - Ở một mình và có thời gian cho bản thân", "E - Thỉnh thoảng giao tiếp, thỉnh thoảng ở một mình", "I - Ở trong một nhóm nhỏ thân thiết"] },
  { text: "Bạn đưa ra quyết định dựa trên yếu tố nào?", type: "T-F", options: ["F - Cảm xúc và mối quan hệ cá nhân", "T - Logic và sự thật khách quan", "F - Cân nhắc cả cảm xúc lẫn lý trí", "T - Dựa vào trực giác và kinh nghiệm trước đó"] },
  { text: "Bạn thích học hỏi theo cách nào?", type: "S-N", options: ["S - Thử nghiệm và học qua thực hành", "N - Đọc sách và nghiên cứu tài liệu", "S - Học qua quan sát người khác", "N - Học qua trải nghiệm cuộc sống"] },
  { text: "Bạn phản ứng thế nào khi gặp một vấn đề khó khăn?", type: "J-P", options: ["J - Tìm cách giải quyết ngay lập tức", "P - Phân tích kỹ rồi mới hành động", "J - Tìm kiếm lời khuyên từ người khác", "P - Tránh né và chờ tình hình rõ ràng hơn"] },
  { text: "Bạn thấy thoải mái hơn với công việc kiểu nào?", type: "J-P", options: ["J - Công việc có cấu trúc và nguyên tắc rõ ràng", "P - Công việc linh hoạt và sáng tạo", "J - Công việc có tính cân bằng giữa cả hai", "P - Công việc có nhiều sự thay đổi và thử thách"] },
  { text: "Điều gì khiến bạn cảm thấy hài lòng hơn?", type: "T-F", options: ["F - Giúp đỡ người khác và tạo kết nối", "T - Hoàn thành một nhiệm vụ một cách xuất sắc", "F - Khám phá điều mới mẻ và mở rộng hiểu biết", "T - Trải nghiệm cảm giác yên bình và tự do"] },
  { text: "Bạn thường phản ứng thế nào với những thay đổi bất ngờ?", type: "J-P", options: ["J - Cảm thấy hào hứng và sẵn sàng thích nghi", "P - Cần thời gian để thích nghi dần dần", "J - Phụ thuộc vào mức độ quan trọng của thay đổi", "P - Cố gắng duy trì kế hoạch ban đầu nếu có thể"] },
  { text: "Bạn cảm thấy thế nào khi làm việc với nhiều quy tắc?", type: "J-P", options: ["J - Tôn trọng và tuân theo để đạt hiệu quả cao", "P - Cảm thấy gò bó và thích sáng tạo hơn", "J - Thích nghi nếu quy tắc hợp lý", "P - Cố gắng thay đổi quy tắc nếu thấy không phù hợp"] },
  { text: "Bạn đánh giá thành công của mình dựa trên điều gì?", type: "T-F", options: ["F - Những mối quan hệ tốt đẹp và ý nghĩa", "T - Thành tựu cá nhân và kết quả cụ thể", "F - Cảm giác hài lòng và sự phát triển bản thân", "T - Sự tự do và khả năng làm điều mình thích"] }
];


let currentQuestionIndex = 0;
let answers = {
  "E-I": { E: 0, I: 0 },
  "S-N": { S: 0, N: 0 },
  "T-F": { T: 0, F: 0 },
  "J-P": { J: 0, P: 0 }
};

// Lưu lựa chọn của từng câu
let selectedAnswers = {};

function showQuestion() {
  const questionText = document.getElementById("question-text");
  const answersContainer = document.getElementById("answers-container");

  if (currentQuestionIndex >= questions.length) {
    showResult();
    return;
  }

  const q = questions[currentQuestionIndex];
  questionText.innerText = `Câu ${currentQuestionIndex + 1}: ${q.text}`;
  answersContainer.innerHTML = "";

  q.options.forEach((option, index) => {
    const answerDiv = document.createElement("div");
    answerDiv.classList.add("answer-box");
    answerDiv.innerText = option;
    answerDiv.onclick = () => selectAnswer(index, answerDiv);

    // Kiểm tra xem đã chọn đáp án nào trước đó chưa
    if (selectedAnswers[currentQuestionIndex] === index) {
      answerDiv.classList.add("answer-selected");
    }

    answersContainer.appendChild(answerDiv);
  });

  document.getElementById("prevBtn").disabled = currentQuestionIndex === 0;
}

function selectAnswer(optionIndex, element) {
  const q = questions[currentQuestionIndex];
  const selectedType = q.type;
  const selectedValue = q.options[optionIndex][0];

  // Nếu trước đó đã chọn một đáp án khác, trừ điểm đáp án cũ
  if (selectedAnswers[currentQuestionIndex] !== undefined) {
    const prevValue = q.options[selectedAnswers[currentQuestionIndex]][0];
    answers[selectedType][prevValue]--;
  }

  // Lưu đáp án đã chọn
  selectedAnswers[currentQuestionIndex] = optionIndex;
  answers[selectedType][selectedValue]++;

  // Cập nhật giao diện
  document.querySelectorAll(".answer-box").forEach(box => {
    box.classList.remove("answer-selected");
  });
  element.classList.add("answer-selected");
}

function nextQuestion() {
  if (selectedAnswers[currentQuestionIndex] === undefined) {
    showCustomAlert("Vui lòng chọn một câu trả lời!");
    return;
  }
  currentQuestionIndex++;
  showQuestion();
}

function prevQuestion() {
  if (currentQuestionIndex > 0) {
    currentQuestionIndex--;
    showQuestion();
  }
}

function showResult() {
  let mbti = "";
  mbti += (answers["E-I"].E > answers["E-I"].I) ? "E" : "I";
  mbti += (answers["S-N"].S > answers["S-N"].N) ? "S" : "N";
  mbti += (answers["T-F"].T > answers["T-F"].F) ? "T" : "F";
  mbti += (answers["J-P"].J > answers["J-P"].P) ? "J" : "P";

  // Danh sách biệt danh MBTI
  const mbtiDescriptions = {
    "ENFJ": "Nhà lãnh đạo truyền cảm hứng",
    "ENFP": "Nhà thám hiểm sáng tạo",
    "ENTJ": "Nhà lãnh đạo chiến lược",
    "ENTP": "Nhà tranh luận thông minh",
    "ESFJ": "Người quan tâm đến cộng đồng",
    "ESFP": "Người nghệ sĩ vui vẻ",
    "ESTJ": "Nhà điều hành kỷ luật",
    "ESTP": "Nhà thám hiểm hành động",
    "INFJ": "Người bảo vệ lý tưởng",
    "INFP": "Người mơ mộng",
    "INTJ": "Nhà chiến lược tương lai",
    "INTP": "Nhà tư duy logic",
    "ISFJ": "Người bảo vệ tận tâm",
    "ISFP": "Nghệ sĩ tự do",
    "ISTJ": "Người có trách nhiệm",
    "ISTP": "Người thợ thủ công"
  };

  const mbtiTitle = mbtiDescriptions[mbti] || "Tính cách chưa xác định";

  document.getElementById("question-text").innerText = "Kết quả MBTI của bạn:";

  document.getElementById("progress-container").style.display = "none";

  document.getElementById("answers-container").innerHTML = `
    <h2 style="font-size: 40px;">${mbti}</h2>
    <p style="font-size: 24px; font-weight: bold;">${mbtiTitle}</p>
  `;

  document.getElementById("prevBtn").style.display = "none";
  document.getElementById("nextBtn").style.display = "none";
}




function updateProgressBar() {
  const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
  document.getElementById("progress-bar").style.width = `${progress}%`;
}

function showQuestion() {
  const questionText = document.getElementById("question-text");
  const answersContainer = document.getElementById("answers-container");

  if (currentQuestionIndex >= questions.length) {
    showResult();
    return;
  }

  updateProgressBar(); // Cập nhật tiến trình

  const q = questions[currentQuestionIndex];
  questionText.innerText = `Câu ${currentQuestionIndex + 1}: ${q.text}`;
  answersContainer.innerHTML = "";

  q.options.forEach((option, index) => {
    const answerDiv = document.createElement("div");
    answerDiv.classList.add("answer-box");
    answerDiv.innerText = option;
    answerDiv.onclick = () => selectAnswer(index, answerDiv);

    if (selectedAnswers[currentQuestionIndex] === index) {
      answerDiv.classList.add("answer-selected");
    }

    answersContainer.appendChild(answerDiv);
  });

  document.getElementById("prevBtn").disabled = currentQuestionIndex === 0;
}


// Hiển thị câu hỏi đầu tiên khi trang tải lên
showQuestion();
