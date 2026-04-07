<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Post::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $admin = User::where('role', 'admin')->first() ?? User::first();
        $cats  = Category::where('type', 'blog')->pluck('id', 'slug');

        $posts = [
            [
                'title'        => '10 loại rau củ giúp tăng cường hệ miễn dịch hiệu quả',
                'slug'         => '10-loai-rau-cu-giup-tang-cuong-he-mien-dich',
                'excerpt'      => 'Hệ miễn dịch khỏe mạnh là nền tảng của sức khỏe tốt. Khám phá 10 loại rau củ dễ tìm, giàu vitamin và khoáng chất giúp bảo vệ cơ thể bạn.',
                'content'      => '<p>Hệ miễn dịch là hàng rào bảo vệ cơ thể khỏi vi khuẩn, virus và các tác nhân gây bệnh. Chế độ ăn uống đóng vai trò quan trọng trong việc duy trì và tăng cường hệ miễn dịch.</p><h3>1. Tỏi</h3><p>Tỏi chứa allicin - hợp chất có tính kháng khuẩn và kháng virus mạnh. Ăn 1-2 tép tỏi mỗi ngày giúp tăng cường đề kháng đáng kể.</p><h3>2. Gừng</h3><p>Gừng có tính kháng viêm và chống oxy hóa cao. Trà gừng mật ong là thức uống tuyệt vời cho hệ miễn dịch.</p><h3>3. Cải bó xôi</h3><p>Giàu vitamin C, beta-carotene và nhiều chất chống oxy hóa. Nên ăn sống hoặc nấu chín nhẹ để giữ dưỡng chất.</p>',
                'thumbnail'    => 'theme/images/blog/blog-1.jpg',
                'category_slug'=> 'suc-khoe-dinh-duong',
                'status'       => 'published',
                'is_featured'  => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title'        => 'Công thức làm salad rau củ trộn dầu giấm đơn giản tại nhà',
                'slug'         => 'cong-thuc-lam-salad-rau-cu-tron-dau-giam',
                'excerpt'      => 'Salad rau củ trộn dầu giấm là món ăn nhẹ, thanh mát, dễ làm và rất tốt cho sức khỏe. Cùng học cách làm ngay tại nhà nhé!',
                'content'      => '<p>Salad rau củ là lựa chọn hoàn hảo cho bữa ăn nhẹ hoặc khai vị. Với công thức đơn giản này, bạn có thể tự làm tại nhà trong vòng 15 phút.</p><h3>Nguyên liệu</h3><ul><li>Xà lách xanh: 200g</li><li>Cà chua bi: 100g</li><li>Dưa leo: 1 quả</li><li>Cà rốt: 1 củ</li><li>Dầu olive: 3 muỗng canh</li><li>Giấm táo: 2 muỗng canh</li><li>Mật ong: 1 muỗng cà phê</li></ul><h3>Cách làm</h3><p>Rửa sạch tất cả rau củ. Cắt nhỏ vừa ăn. Trộn đều dầu olive, giấm táo và mật ong làm nước sốt. Rưới lên rau và trộn đều.</p>',
                'thumbnail'    => 'theme/images/blog/blog-2.jpg',
                'category_slug'=> 'cong-thuc-nau-an',
                'status'       => 'published',
                'is_featured'  => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title'        => 'Mẹo bảo quản rau củ quả tươi lâu hơn trong tủ lạnh',
                'slug'         => 'meo-bao-quan-rau-cu-qua-tuoi-lau-hon',
                'excerpt'      => 'Bảo quản rau củ đúng cách giúp giữ được độ tươi ngon và dưỡng chất lâu hơn. Khám phá những mẹo hay từ các chuyên gia dinh dưỡng.',
                'content'      => '<p>Rau củ quả tươi thường có thời hạn sử dụng ngắn nếu không được bảo quản đúng cách. Dưới đây là những mẹo giúp kéo dài độ tươi của rau củ.</p><h3>Phân loại trước khi cất</h3><p>Không nên để tất cả rau củ chung một ngăn. Một số loại như táo, chuối tỏa ra khí ethylene làm chín nhanh các loại khác.</p><h3>Bọc đúng cách</h3><p>Dùng túi vải hoặc giấy ẩm để bọc rau lá. Tránh dùng túi nilon kín vì sẽ làm rau bị ủng.</p><h3>Nhiệt độ phù hợp</h3><p>Rau lá: 0-4°C. Cà chua, khoai tây: nhiệt độ phòng. Trái cây nhiệt đới: không nên để tủ lạnh.</p>',
                'thumbnail'    => 'theme/images/blog/blog-3.jpg',
                'category_slug'=> 'meo-vat-nha-bep',
                'status'       => 'published',
                'is_featured'  => false,
                'published_at' => now()->subDays(8),
            ],
            [
                'title'        => 'Xu hướng thực phẩm hữu cơ tại Việt Nam năm 2025',
                'slug'         => 'xu-huong-thuc-pham-huu-co-tai-viet-nam-2025',
                'excerpt'      => 'Thị trường thực phẩm hữu cơ Việt Nam đang tăng trưởng mạnh mẽ. Tìm hiểu những xu hướng nổi bật và cơ hội cho người tiêu dùng.',
                'content'      => '<p>Thị trường thực phẩm hữu cơ tại Việt Nam đã tăng trưởng hơn 30% trong năm 2024 và dự kiến tiếp tục tăng mạnh trong 2025.</p><h3>Người tiêu dùng ngày càng quan tâm đến sức khỏe</h3><p>Sau đại dịch COVID-19, người Việt Nam ngày càng chú trọng đến chất lượng thực phẩm và nguồn gốc xuất xứ. Nhu cầu về thực phẩm hữu cơ, sạch tăng cao.</p><h3>Các sản phẩm hữu cơ phổ biến nhất</h3><p>Rau củ hữu cơ, trứng gà thả vườn, thịt sạch và các loại hạt dinh dưỡng đang dẫn đầu xu hướng tiêu dùng.</p>',
                'thumbnail'    => 'theme/images/blog/blog-4.jpg',
                'category_slug'=> 'tin-tuc-thuc-pham',
                'status'       => 'published',
                'is_featured'  => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'title'        => 'Lợi ích tuyệt vời của việc ăn trái cây mỗi ngày',
                'slug'         => 'loi-ich-tuyet-voi-cua-viec-an-trai-cay-moi-ngay',
                'excerpt'      => 'Ăn đủ 2-3 phần trái cây mỗi ngày mang lại vô số lợi ích cho sức khỏe. Cùng tìm hiểu tại sao trái cây là "thuốc bổ" tự nhiên tốt nhất.',
                'content'      => '<p>Tổ chức Y tế Thế giới (WHO) khuyến nghị mỗi người nên ăn ít nhất 400g trái cây và rau củ mỗi ngày để duy trì sức khỏe tốt.</p><h3>Giàu vitamin và khoáng chất</h3><p>Trái cây là nguồn cung cấp vitamin C, vitamin A, kali và folate tự nhiên. Những dưỡng chất này thiết yếu cho nhiều chức năng của cơ thể.</p><h3>Hỗ trợ tiêu hóa</h3><p>Chất xơ trong trái cây giúp duy trì hệ tiêu hóa khỏe mạnh, ngăn ngừa táo bón và giảm nguy cơ ung thư đại tràng.</p>',
                'thumbnail'    => 'theme/images/blog/blog-5.jpg',
                'category_slug'=> 'suc-khoe-dinh-duong',
                'status'       => 'published',
                'is_featured'  => false,
                'published_at' => now()->subDays(15),
            ],
            [
                'title'        => 'Cách nấu phở bò truyền thống chuẩn vị Hà Nội',
                'slug'         => 'cach-nau-pho-bo-truyen-thong-chuan-vi-ha-noi',
                'excerpt'      => 'Phở bò Hà Nội với nước dùng trong vắt, thơm ngọt từ xương và gia vị. Học cách nấu phở chuẩn vị ngay tại nhà với công thức chi tiết này.',
                'content'      => '<p>Phở bò là món ăn quốc hồn quốc túy của Việt Nam. Để có nồi phở ngon, bí quyết nằm ở nước dùng được ninh từ xương bò trong nhiều giờ.</p><h3>Nguyên liệu (6 người ăn)</h3><ul><li>Xương bò: 2kg</li><li>Thịt bò: 500g</li><li>Bánh phở: 1kg</li><li>Hành tây: 2 củ</li><li>Gừng: 1 củ lớn</li><li>Hoa hồi, quế, thảo quả</li></ul><h3>Cách làm</h3><p>Chần xương qua nước sôi để loại bỏ tạp chất. Nướng hành tây và gừng cho thơm. Ninh xương 6-8 tiếng với gia vị. Lọc lấy nước trong.</p>',
                'thumbnail'    => 'theme/images/blog/blog-6.jpg',
                'category_slug'=> 'cong-thuc-nau-an',
                'status'       => 'published',
                'is_featured'  => false,
                'published_at' => now()->subDays(20),
            ],
        ];

        foreach ($posts as $data) {
            $catSlug = $data['category_slug'];
            unset($data['category_slug']);
            $data['category_id'] = $cats[$catSlug] ?? null;
            $data['author_id']   = $admin?->id;
            Post::create($data);
        }

        $this->command->info('✅ Đã seed ' . Post::count() . ' bài viết');
    }
}
