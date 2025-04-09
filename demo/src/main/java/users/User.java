package users;


import jakarta.persistence.*;

@Entity
@Table(name = "users")  // Đảm bảo tên bảng là "users"
public class User {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String location;

    private String username;
    private String password;
    private String gender;
    private int age;
    private String mbti; // ISTJ, ENFP, INFJ,...
    private String interests; // Danh sách sở thích

    private int preferredMinAge;
    private int preferredMaxAge;
    private String preferredLocation;

    public User(){}

    public User(long l, String name, String mbti, int age) {
        this.id = l;
        this.username = name;
        this.mbti = mbti;
        this.age = age;

    }


    ///REAL
    public User(long id, String name, String mbti, int age, String location) {
        this.id = id;
        this.username = name;
        this.mbti = mbti;
        this.age = age;
        this.location = location;
    }
//test api
//    public User(long l, String name, String mbti, int i) {
//        this.id = l;
//        this.username = name;
//        this.mbti = mbti;
//        this.age = i;
//    }

    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getGender() {
        return gender;
    }

    public void setGender(String gender) {
        this.gender = gender;
    }

    public int getAge() {
        return age;
    }

    public void setAge(int age) {
        this.age = age;
    }

    public String getMbti() {
        return mbti;
    }

    public void setMbti(String mbti) {
        this.mbti = mbti;
    }

    public String getInterests() {
        return interests;
    }

    public void setInterests(String interests) {
        this.interests = interests;
    }


    public int getPreferredMinAge() {
        return preferredMinAge;
    }

    public void setPreferredMinAge(int preferredMinAge) {
        this.preferredMinAge = preferredMinAge;
    }

    public int getPreferredMaxAge() {
        return preferredMaxAge;
    }

    public void setPreferredMaxAge(int preferredMaxAge) {
        this.preferredMaxAge = preferredMaxAge;
    }

    public String getPreferredLocation() {
        return preferredLocation;
    }

    public void setPreferredLocation(String preferredLocation) {
        this.preferredLocation = preferredLocation;
    }

    // Getters and Setters
}
