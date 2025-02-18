<?php

class Membership {

    private int $userId; // Foreign key to users table
    private int $clubId; // Foreign key to clubs table
    private string $joinedAt;

    public function __construct(
        int $userId = 0,
        int $clubId = 0,
        string $joinedAt = ""
    ) {
        $this->userId = $userId;
        $this->clubId = $clubId;
        $this->joinedAt = $joinedAt;
    }

    // Getters
    public function getUserId(): int {
        return $this->userId;
    }

    public function getClubId(): int {
        return $this->clubId;
    }

    public function getJoinedAt(): string {
        return $this->joinedAt;
    }

    // Setters (use with caution and validation)
    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }

    public function setClubId(int $clubId): void {
        $this->clubId = $clubId;
    }


    // Example Methods (Adapt as needed)

    public function save($conn): bool { // Save a new membership
        $stmt = $conn->prepare("INSERT INTO memberships (user_id, club_id) VALUES (?,?)");
        $stmt->bind_param("ii", $this->userId, $this->clubId);
        return $stmt->execute();
    }

    public static function findByUserIdAndClubId(int $userId, int $clubId, $conn) {
        $stmt = $conn->prepare("SELECT * FROM memberships WHERE user_id =? AND club_id =?");
        $stmt->bind_param("ii", $userId, $clubId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $membershipData = $result->fetch_assoc();
            return new Membership(
                $membershipData['user_id'],
                $membershipData['club_id'],
                $membershipData['joined_at']
            );
        }
        return null;
    }


    public function delete($conn): bool { // Remove a membership
        $stmt = $conn->prepare("DELETE FROM memberships WHERE user_id =? AND club_id =?");
        $stmt->bind_param("ii", $this->userId, $this->clubId);
        return $stmt->execute();
    }

    //... other methods as needed

}