//package users;
//
//
//import jakarta.persistence.*;
//
//@Entity
//@Table(name = "matches") // Đổi tên bảng
//public class Match {
//    @Id
//    @GeneratedValue(strategy = GenerationType.IDENTITY)
//    private Long id;
//
//    @ManyToOne
//    @JoinColumn(name = "user1_id")
//    private User user1;
//
//    @ManyToOne
//    @JoinColumn(name = "user2_id")
//    private User user2;
//
//    private double compatibilityScore; // Điểm độ phù hợp
//
//    public Match() {}
//
//    public Match(User user1, User user2, double compatibilityScore) {
//        this.user1 = user1;
//        this.user2 = user2;
//        this.compatibilityScore = compatibilityScore;
//    }
//
//    public Long getId() { return id; }
//    public User getUser1() { return user1; }
//    public User getUser2() { return user2; }
//    public double getCompatibilityScore() { return compatibilityScore; }
//}
