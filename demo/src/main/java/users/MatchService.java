package users;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.Arrays;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

@Service
public class MatchService {

    @Autowired
    private UserRepository userRepository;

    // 🔎 Tìm người trong bán kính nhất định
    public List<User> findNearby(double lat, double lon, int radius) {
        return userRepository.findAll().stream()
                .filter(user -> calculateDistance(lat, lon, user.getLatitude(), user.getLongitude()) <= radius)
                .collect(Collectors.toList());
    }

//    // 💕 Tìm người phù hợp theo MBTI
//    public List<User> findMatches(Long userId) {
//        User user = userRepository.findById(userId).orElse(null);
//        if (user == null) return List.of();
//
//        return userRepository.findAll().stream()
//                .filter(u -> !u.getId().equals(userId)) // Không ghép chính mình
//                .filter(u -> calculateDistance(user.getLatitude(), user.getLongitude(), u.getLatitude(), u.getLongitude()) <= 5) // 🔥 Chỉ lấy những người cách dưới 5km
//                .map(u -> new UserMatch(u, calculateCompatibility(user, u))) // Tính điểm tương thích
//                .sorted((a, b) -> Double.compare(b.getCompatibilityScore(), a.getCompatibilityScore())) // Sắp xếp giảm dần
//                .map(UserMatch::getUser)
//                .collect(Collectors.toList());
//    }

    public List<User> findMatches(Long userId) {
        System.out.println("🔍 API findMatches được gọi với ID: " + userId);
        User user = userRepository.findById(userId).orElse(null);
        if (user == null) {
            System.out.println("⚠️ Không tìm thấy user với ID: " + userId);
            return List.of();
        }
         //Tìm kiếm tất cả người dùng có cùng MBTI với người dùng này
        return userRepository.findByMbti(user.getMbti()).stream()
                .filter(u -> !u.getId().equals(userId)) // Loại bỏ người dùng chính mình
                .collect(Collectors.toList()); // Trả về danh sách người dùng có cùng MBTI
    }

//    public List<User> findMatches(Long id) {
//        // Fake danh sách người dùng phù hợp
//        return Arrays.asList(
//                new User(3L, "BerBer", "ISFJ", 27),
//                new User(4L, "Dogneverdie", "ENTP", 22)
//        );
//    }

    // 📍 Tính khoảng cách giữa hai người
    public static double calculateDistance(double lat1, double lon1, double lat2, double lon2) {
        double x = lat2 - lat1;
        double y = lon2 - lon1;
        return Math.sqrt(x * x + y * y) * 111; // 1 độ ≈ 111 km
    }

    // 💞 Tính điểm tương thích MBTI
    private double calculateCompatibility(User user1, User user2) {
        String mbti1 = user1.getMbti();
        String mbti2 = user2.getMbti();

        if (mbti1.equals(mbti2)) return 100; // Cùng loại -> Tương thích 100%

        Map<String, List<String>> compatibilityChart = Map.of(
                "ISTJ", List.of("ISFJ", "ESTJ", "ESFJ"),
                "ISFJ", List.of("ISTJ", "ESFJ", "ESTJ"),
                "ENTP", List.of("INFJ", "INTP"),
                "INFJ", List.of("ENTP", "INTP")
        );

        if (compatibilityChart.containsKey(mbti1) && compatibilityChart.get(mbti1).contains(mbti2)) {
            return 80; // Nếu nằm trong danh sách hợp -> 80 điểm
        }

        return 50; // Mặc định 50 điểm nếu không hợp
    }

    // 📌 Class hỗ trợ lưu thông tin match + điểm tương thích
    private static class UserMatch {
        private final User user;
        private final double compatibilityScore;

        public UserMatch(User user, double compatibilityScore) {
            this.user = user;
            this.compatibilityScore = compatibilityScore;
        }

        public User getUser() {
            return user;
        }

        public double getCompatibilityScore() {
            return compatibilityScore;
        }
    }
}
