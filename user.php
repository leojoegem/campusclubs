<?php

class User {

    private int $id;
    private string $username;
    private string $email;
    private string $password; // Should be hashed in the database
    private string $role;
    private string $createdAt;
    private ?string $otp;          // OTP (nullable)
    private ?string $otpGeneratedAt; // OTP generation timestamp (nullable)

    // Constructor
    public function __construct(
        int $id = 0,
        string $username = "",
        string $email = "",
        string $password = "",
        string $role = "student",
        string $createdAt = "",
        ?string $otp = null,       // Add OTP to constructor
        ?string $otpGeneratedAt = null // Add OTP generation time
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->otp = $otp;
        $this->otpGeneratedAt = $otpGeneratedAt;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    public function getOtp(): ?string {
        return $this->otp;
    }

    public function getOtpGeneratedAt(): ?string {
        return $this->otpGeneratedAt;
    }

    // Setters
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setOtp(?string $otp): void {
        $this->otp = $otp;
    }

    public function setOtpGeneratedAt(?string $otpGeneratedAt): void {
        $this->otpGeneratedAt = $otpGeneratedAt;
    }

    // Other methods
    public function login(): bool {
        // Implement your login logic here
        return false; // Placeholder
    }

    public function register($conn): bool {  // Pass $conn to register
        $stmt = $conn->prepare('INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)');
        $stmt->bind_param('ssss', $this->username, $this->email, $this->password, $this->role);
        $result = $stmt->execute();
        if($result){
          $this->id = $stmt->insert_id; // Set the ID after successful insertion
        }
        return $result; // Placeholder
    }


    // Static method to fetch a user by email
    public static function findByEmail(string $email, $conn) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            return new User(
                $userData['id'],
                $userData['username'],
                $userData['email'],
                $userData['password'],
                $userData['role'],
                $userData['created_at'],
                $userData['otp'],
                $userData['otp_generated_at']
            );
        }
        return null; // User not found
    }

    public function saveOtp($conn, $otp) {
        $this->otp = $otp;
        $this->otpGeneratedAt = date('Y-m-d H:i:s'); // Current timestamp
        $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_generated_at = ? WHERE id = ?");
        $stmt->bind_param("ssi", $this->otp, $this->otpGeneratedAt, $this->id);
        return $stmt->execute();
    }

    public function clearOtp($conn) {
        $this->otp = null;
        $this->otpGeneratedAt = null;
        $stmt = $conn->prepare("UPDATE users SET otp = NULL, otp_generated_at = NULL WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    // ... other methods (updateProfile, resetPassword, etc.)

}
?>