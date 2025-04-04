package users;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;
import java.util.List;

@CrossOrigin(origins = "*")  // Cho phép truy cập từ mọi nguồn
@RestController
@RequestMapping("/users")
public class MatchController {

    @Autowired
    private MatchService matchService;

//    @GetMapping("/findNearby")
//    public List<User> findNearby(@RequestParam double lat, @RequestParam double lon, @RequestParam int radius) {
//        return matchService.findNearby(lat, lon, radius);
//    }

//    @GetMapping("/findMatches")
//    public List<User> findMatches(@RequestParam Long id) {
//        System.out.println("Received request for findMatches with ID: " + id);
//        return matchService.findMatches(id);
//    }
//
//    @GetMapping("/matchMore")
//    public List<User> getMatches(
//            //@PathVariable Long userId,
//            @RequestParam Long userId,
//            @RequestParam(required = false) Integer minAge,
//            @RequestParam(required = false) Integer maxAge,
//            @RequestParam(required = false) String location
//    ) {
//        System.out.println("2.Received request for findMatches with ID: " +userId);
//        return matchService.findMatches(userId, minAge, maxAge, null);
//    }

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

        // Gọi dịch vụ để tìm các người dùng phù hợp
        return matchService.findMatches(id, minAge, maxAge, location);
    }

}
