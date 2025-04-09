package users;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.*;
import java.util.stream.Collectors;

@Service
public class MatchService {

    @Autowired
    private UserRepository userRepository;

    @Transactional
    public List<User> findMatches(Long userId, Integer minAge, Integer maxAge, String location) {
        System.out.println("🔍 API findMatches được lọc thêm nữa: " + userId);

        User user = userRepository.findById(userId).orElse(null);
        if (user == null) {
            System.out.println("⚠DEBUG: Không tìm thấy user với ID: " + userId);
            return List.of();
        }

        // Cập nhật preferredMinAge và preferredMaxAge của user
        if (minAge != null) {
            user.setPreferredMinAge(minAge);
        }
        if (maxAge != null) {
            user.setPreferredMaxAge(maxAge);
        }
        if (location != null && !location.equalsIgnoreCase("null")) {
            user.setPreferredLocation(location);
        }
        userRepository.save(user); // Lưu cập nhật vào database
        System.out.println("💾 Đã cập nhật user: preferredMinAge=" + minAge + ", preferredMaxAge=" + maxAge
                + ", preferredLocation=" + location);

        Map<String, List<String>> compatibilityChart = new HashMap<>();
        compatibilityChart.put("INTJ", List.of("ENFP", "ENTJ", "INFJ", "ENTJ", "ENFP")); // thêm ENFP, ENTJ ngược lại
        compatibilityChart.put("INTP", List.of("ENTJ", "ENTP", "INFP", "ISTP")); // thêm ISTP
        compatibilityChart.put("ENTJ", List.of("INTP", "INTJ", "ENFP", "ISTJ", "ISTP", "ESTP")); // thêm ISTJ, ISTP, ESTP
        compatibilityChart.put("ENTP", List.of("INFJ", "INFP", "ENTJ")); // ok
        compatibilityChart.put("INFJ", List.of("ENTP", "ENFP", "INTJ", "ISFP")); // thêm ISFP
        compatibilityChart.put("INFP", List.of("ENFP", "INFJ", "ENTP", "INTP", "ENFJ")); // thêm INTP, ENFJ
        compatibilityChart.put("ENFJ", List.of("INFP", "INFJ", "ENTP", "ESFJ")); // thêm ESFJ
        compatibilityChart.put("ENFP", List.of("INFJ", "INTJ", "INFP", "INFP", "ISFJ", "ESFP")); // thêm INFP, ISFJ, ESFP
        compatibilityChart.put("ISTJ", List.of("ESTP", "ISFJ", "ENTJ", "ESTJ")); // thêm ESTJ
        compatibilityChart.put("ISFJ", List.of("ESFJ", "ISTJ", "ENFP")); // ok
        compatibilityChart.put("ESTJ", List.of("ISTJ", "ESTP", "ESFJ")); // ok
        compatibilityChart.put("ESFJ", List.of("ISFJ", "ENFJ", "ESTP")); // ok
        compatibilityChart.put("ISTP", List.of("ESTP", "INTP", "ENTJ")); // ok
        compatibilityChart.put("ISFP", List.of("ESFP", "INFJ", "ENFP")); // ok
        compatibilityChart.put("ESTP", List.of("ISTP", "ESFP", "ENTJ", "ISTJ", "ESTJ", "ESFJ")); // thêm ISTJ, ESTJ, ESFJ
        compatibilityChart.put("ESFP", List.of("ISFP", "ESTP", "ENFP")); // ok

        // Lọc theo MBTI
        List<String> compatibleMbtiList = compatibilityChart.getOrDefault(user.getMbti(), List.of());
        List<User> matchedUsers = userRepository.findByMbtiIn(compatibleMbtiList);

        // Lọc theo tuổi nếu có yêu cầu
//        if (minAge != null && maxAge != null) {
//            matchedUsers = matchedUsers.stream()
//                    .filter(u -> u.getAge() >= minAge && u.getAge() <= maxAge)
//                    .collect(Collectors.toList());
//        }

        // Lọc theo tuổi nếu có yêu cầu
        if (minAge != null) {
            matchedUsers = matchedUsers.stream()
                    .filter(u -> u.getAge() >= minAge)
                    .collect(Collectors.toList());
        }

        if (maxAge != null) {
            matchedUsers = matchedUsers.stream()
                    .filter(u -> u.getAge() <= maxAge)
                    .collect(Collectors.toList());
        }
        // Lọc theo location nếu có yêu cầu
        if (location != null) {
            matchedUsers = matchedUsers.stream()
                    .filter(u -> location.equalsIgnoreCase(u.getLocation()))
                    .collect(Collectors.toList());
        }
        //ko lấy chính mình
        return matchedUsers.stream()
                .filter(u -> !u.getId().equals(userId))
                .collect(Collectors.toList());
    }


    // 📌 Class hỗ trợ lưu thông tin match
//    private static class UserMatch {
//        private final User user;
//        private final double compatibilityScore;
//
//        public UserMatch(User user, double compatibilityScore) {
//            this.user = user;
//            this.compatibilityScore = compatibilityScore;
//        }
//
//        public User getUser() {
//            return user;
//        }
//
//        public double getCompatibilityScore() {
//            return compatibilityScore;
//        }
//    }
}
