<?php
$connect = mysqli_connect("localhost", "root", "", "todoproject") or die("failed");

if ($connect) {
    echo "connected";
} else {
    echo "no";
}
?>
