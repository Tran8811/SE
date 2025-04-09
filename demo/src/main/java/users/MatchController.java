package users;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import java.util.List;
import java.util.Optional;

@CrossOrigin(origins = "*")  // Cho phép truy cập từ mọi nguồn
@RestController
@RequestMapping("/users")
public class MatchController {

    @Autowired
    private MatchService matchService;
    @Autowired
    private UserRepository userRepository;

    // API lấy thông tin user theo id
    @GetMapping("/{id}")
    public ResponseEntity<User> getUserById(@PathVariable("id") Long id) {
        System.out.println("Received request for user with ID: " + id);
        Optional<User> user = userRepository.findById(id);
        if (user.isPresent()) {
            System.out.println("User found: " + user.get().getUsername());
            return ResponseEntity.ok(user.get());
        } else {
            System.out.println("User with ID " + id + " not found");
            return ResponseEntity.status(404).body(null);
        }
    }
    // API tìm kiếm người phù hợp theo MBTI và các tham số khác
    @GetMapping("/findMatches")
    public List<User> findMatches(
            @RequestParam Long id,
            @RequestParam(required = false) Integer minAge,
            @RequestParam(required = false) Integer maxAge,
            @RequestParam(required = false) String location
    ) {
        if (location != null) {
            location = location.replace("+", " ").replace("%20", " ");
        }
        System.out.println("Received request for findMatches with ID: " + id);
        System.out.println("Received request for Age: " + minAge + "-" + maxAge);

        // Gọi dịch vụ để tìm các người dùng phù hợp
        return matchService.findMatches(id, minAge, maxAge, location);
    }

}
