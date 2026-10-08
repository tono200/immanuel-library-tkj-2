<?php
if (isset($_POST['name'])) {
  echo "<h3>Data pengguna baru diterima:</h3>";
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
} else {
  echo "Tidak ada data yang dikirim.";
}