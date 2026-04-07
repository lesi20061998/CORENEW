<?php
// Xóa link cũ nếu có
$link = __DIR__ . '/public/storage';
if (is_link($link)) {
    unlink($link);
}
// Tạo link mới với đường dẫn tuyệt đối
$target = __DIR__ . '/storage/app/public';
if (symlink($target, $link)) {
    echo "Đã tạo Storage Link thành công!";
} else {
    echo "Lỗi: Không thể tạo Storage Link.";
}
?>