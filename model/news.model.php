<?php
// model/news.model.php

function getAllNews($conn) {
    $sql = "SELECT * FROM news ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $news = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $news[] = $row;
        }
    }

    return $news;
}
// Lấy tin tức theo ID
function getNewsById($conn, $id) {
    $sql = "SELECT * FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
// Lấy tin tức khác (ngoại trừ bài hiện tại)
function getOtherNews($conn, $currentId, $limit = 10) {
    $sql = "SELECT * FROM news WHERE id != ? ORDER BY created_at DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $currentId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $news = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $news[] = $row;
        }
    }
    return $news;
}


?>