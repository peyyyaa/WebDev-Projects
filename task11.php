<?php

class Book {
    public $title;
    public $author;
    public $year;
    public $isAvailable;

    public function __construct($title, $author, $year, $isAvailable) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->isAvailable = $isAvailable;
    }

    public function displayBookInfo() {
        return "<br>Title: $this->title <br> Author: $this->author <br> Year: $this->year <br> Available: " . ($this->isAvailable ? "Yes" : "No");
    }

    public function borrowBook() {
        if ($this->isAvailable) {
            $this->isAvailable = false;
            return "<br>You have borrowed '$this->title'.";
            displayBookInfo();
        } else {
            return "<br>Sorry, '$this->title' is currently unavailable.";
            displayBookInfo();
        }
    }

    public function returnBook() {
        $this->isAvailable = true;
        return "<br>You have returned '$this->title'.";
    }
}

// Create objects
$Book1 = new Book("Rizal's Life and Works", "Jose Rizal", 2020, true);

echo $Book1->displayBookInfo(); // Display book info
echo "<br>";
echo $Book1->borrowBook();
echo $Book1->displayBookInfo(); // Borrow the book
echo "<br>";
echo $Book1->returnBook();
echo $Book1->displayBookInfo(); // Return the book

?>