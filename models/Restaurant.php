<?php
class Restaurant {
    private $conn;
    private $table_name = "restaurants";

    public $id;
    public $name;
    public $description;
    public $address;
    public $city;
    public $category;
    public $price_range;
    public $rating;
    public $image;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Helper function untuk sanitize
    private function sanitize($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    // Create
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 (name, description, address, city, category, price_range, rating, image) 
                 VALUES (:name, :description, :address, :city, :category, :price_range, :rating, :image)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->name = $this->sanitize($this->name);
        $this->description = $this->sanitize($this->description);
        $this->address = $this->sanitize($this->address);
        $this->city = $this->sanitize($this->city);
        $this->category = $this->sanitize($this->category);
        
        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":price_range", $this->price_range);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":image", $this->image);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all with pagination
    public function readAll($page = 1, $records_per_page = 5, $search = '') {
        $offset = ($page - 1) * $records_per_page;
        
        $query = "SELECT * FROM " . $this->table_name;
        
        if(!empty($search)) {
            $query .= " WHERE name LIKE :search OR description LIKE :search OR category LIKE :search";
        }
        
        $query .= " ORDER BY created_at DESC LIMIT :offset, :records_per_page";
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($search)) {
            $search_term = "%$search%";
            $stmt->bindParam(":search", $search_term);
        }
        
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    // Count total records for pagination
    public function countAll($search = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        if(!empty($search)) {
            $query .= " WHERE name LIKE :search OR description LIKE :search OR category LIKE :search";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($search)) {
            $search_term = "%$search%";
            $stmt->bindParam(":search", $search_term);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    // Read single
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->address = $row['address'];
            $this->city = $row['city'];
            $this->category = $row['category'];
            $this->price_range = $row['price_range'];
            $this->rating = $row['rating'];
            $this->image = $row['image'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Update
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET name = :name, description = :description, address = :address, 
                     city = :city, category = :category, price_range = :price_range, 
                     rating = :rating, image = :image, updated_at = NOW() 
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize
        $this->name = $this->sanitize($this->name);
        $this->description = $this->sanitize($this->description);
        $this->address = $this->sanitize($this->address);
        $this->city = $this->sanitize($this->city);
        $this->category = $this->sanitize($this->category);
        
        // Bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":price_range", $this->price_range);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>