<?php

class Club {

    private int $id;
    private string $name;
    private string $description;
    private int $createdBy; // Foreign key referencing users table
    private string $createdAt;

    // Constructor
    public function __construct(
        int $id = 0,
        string $name = "",
        string $description = "",
        int $createdBy = 0,
        string $createdAt = ""
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getCreatedBy(): int {
        return $this->createdBy;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }


    // Setters (use with caution, validate input where necessary)
    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setCreatedBy(int $createdBy): void {
        $this->createdBy = $createdBy;
    }


    // Example Methods (adapt these to your specific needs)

    public function create($conn): bool {  // Pass the database connection
        $stmt = $conn->prepare("INSERT INTO clubs (name, description, created_by) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $this->name, $this->description, $this->createdBy); // "ssi" - string, string, integer
        $result = $stmt->execute();
        if ($result) {
            $this->id = $stmt->insert_id; // Set the ID after successful insertion
            return true;
        } else {
            return false;
        }
    }

    public static function findById(int $id, $conn) {
        $stmt = $conn->prepare("SELECT * FROM clubs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $clubData = $result->fetch_assoc();
            return new Club(
                $clubData['id'],
                $clubData['name'],
                $clubData['description'],
                $clubData['created_by'],
                $clubData['created_at']
            );
        }
        return null;
    }

    public function update($conn): bool {
        $stmt = $conn->prepare("UPDATE clubs SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $this->name, $this->description, $this->id);
        return $stmt->execute();
    }

    public function delete($conn): bool {
        $stmt = $conn->prepare("DELETE FROM clubs WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }



    // ... other methods (getMembers, addMember, removeMember, etc.)

}