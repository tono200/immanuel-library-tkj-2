<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  echo "<h3>Penulis dengan id $id berhasil dihapus.</h3>";
  echo '<p><a href="../../pages/authors/index.php">Kembali ke daftar penulis</a></p>';
} else {
  echo "id penulis tidak ditemukan.";
}