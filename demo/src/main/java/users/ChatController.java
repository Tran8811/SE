//package users;
//import org.springframework.http.ResponseEntity;
//import org.springframework.web.bind.annotation.GetMapping;
//import org.springframework.web.bind.annotation.RequestParam;
//import org.springframework.web.bind.annotation.RestController;
//import org.springframework.web.client.RestTemplate;
//
//@RestController
//public class ChatController {
//
//    private final RestTemplate restTemplate = new RestTemplate();
//    private static final String FASTAPI_URL = "http://localhost:8080/chat?query=";
//
//    @GetMapping("/ask")
//    public ResponseEntity<String> askQuestion(@RequestParam String query) {
//        String url = FASTAPI_URL + query;
//        String response = restTemplate.getForObject(url, String.class);
//        return ResponseEntity.ok(response);
//    }
//}
package users;
import org.springframework.http.*;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.client.RestTemplate;
import java.util.HashMap;
import java.util.Map;

@RestController
public class ChatController {

    private final RestTemplate restTemplate = new RestTemplate();
    private static final String CHATBOT_SERVICE_URL = "http://localhost:8000/answer";

    @PostMapping("/ask")
    public ResponseEntity<String> askQuestion(@RequestBody Map<String, String> request) {
        String question = request.get("question");

        if (question == null || question.trim().isEmpty()) {
            return ResponseEntity.badRequest().body("Bạn chưa nhập câu hỏi!");
        }

        // Tạo body cho request gửi tới chatbot service
        Map<String, String> body = new HashMap<>();
        body.put("question", question);

        // Thiết lập header
        HttpHeaders headers = new HttpHeaders();
        headers.setContentType(MediaType.APPLICATION_JSON);
        HttpEntity<Map<String, String>> entity = new HttpEntity<>(body, headers);

        // Gửi yêu cầu tới chatbot service và nhận phản hồi
        try {
            ResponseEntity<Map> response = restTemplate.postForEntity(CHATBOT_SERVICE_URL, entity, Map.class);
            String answer = (String) response.getBody().get("answer");
            return ResponseEntity.ok(answer);
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).body("Lỗi kết nối chatbot!");
        }
    }
}