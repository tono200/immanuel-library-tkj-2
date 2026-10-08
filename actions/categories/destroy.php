<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  echo "<h3>Kategori dengan id $id berhasil dihapus.</h3>";
  echo '<p><a href="../../pages/categories/index.php">Kembali ke daftar kategori</a></p>';
} else {
  echo "id kategori tidak ditemukan.";
}