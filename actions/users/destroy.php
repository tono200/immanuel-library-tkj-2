<?php
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Allow: POST");
    http_response_code(405);
    exit("Metode request tidak diizinkan.");
}

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit("ID pengguna tidak valid.");
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(["id" => $id]);

    $status = $stmt->rowCount() > 0 ? "deleted" : "not_found";
    header("Location: ../../pages/users/index.php?status=" . $status);
    exit;
} catch (PDOException $error) {
    error_log($error->getMessage());
    http_response_code(500);
    exit("Pengguna gagal dihapus.");
}
