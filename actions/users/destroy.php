<?php
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  echo "<h3>Pengguna dengan id $id berhasil dihapus.</h3>";
  echo '<p><a href="../../pages/users/index.php">Kembali ke daftar pengguna</a></p>';
} else {
  echo "id pengguna tidak ditemukan.";
}