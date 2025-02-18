<?php

class Alumni {

    private int $id;
    private int $userId; // Foreign key to users table
    private int $clubId; // Foreign key to clubs table
    private string $leftAt;

    public function __construct(
        int $id = 0,
        int $userId = 0,
        int $clubId = 0,
        string $leftAt = ""
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->clubId = $clubId;
        $this->leftAt = $leftAt;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function getClubId(): int {
        return $this->clubId;
    }

    public function getLeftAt(): string {
        return $this->leftAt;
    }

    // Setters (with validation as needed)
    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function setClubId(int $clubId): void {
        $this->clubId = $clubId;
    }

    // Example Methods (Adapt as needed)

    public function save($conn): bool { // Save a new alumni record
        $stmt = $conn->prepare("INSERT INTO alumni (user_id, club_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $this->userId, $this->clubId);
        $result = $stmt->execute();
        if ($result) {
            $this->id = $stmt->insert_id; // Get the ID after successful insertion
        }
        return $result;
    }

    public static function findById(int $id, $conn) {
        $stmt = $conn->prepare("SELECT * FROM alumni WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $alumniData = $result->fetch_assoc();
            return new Alumni(
                $alumniData['id'],
                $alumniData['user_id'],
                $alumniData['club_id'],
                $alumniData['left_at']
            );
        }
        return null;
    }

    public static function findByUserIdAndClubId(int $userId, int $clubId, $conn) {
        $stmt = $conn->prepare("SELECT * FROM alumni WHERE user_id = ? AND club_id = ?");
        $stmt->bind_param("ii", $userId, $clubId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $alumniData = $result->fetch_assoc();
            return new Alumni(
                $alumniData['id'],
                $alumniData['user_id'],
                $alumniData['club_id'],
                $alumniData['left_at']
            );
        }
        return null;
    }

    public function update($conn): bool { // Update alumni record (if needed) - usually the left_at date
        $stmt = $conn->prepare("UPDATE alumni SET left_at = ? WHERE id = ?");
        $stmt->bind_param("si", $this->leftAt, $this->id);
        return $stmt->execute();
    }


    public function delete($conn): bool { // Remove alumni record (if needed)
        $stmt = $conn->prepare("DELETE FROM alumni WHERE id = ?"); // Or by user/club ID
        $stmt->bind_param("i", $this->id); // Or bind user/club IDs
        return $stmt->execute();
    }

    // ... other methods as needed

}