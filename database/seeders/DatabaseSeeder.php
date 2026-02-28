<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───────────────────────────────────────────────────────
        Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('1234'),
        ]);

        // ─── Categories ──────────────────────────────────────────────────
        $cats = [
            ['name' => 'Smartphones',  'icon' => '📱'],
            ['name' => 'Laptops',      'icon' => '💻'],
            ['name' => 'Audio',        'icon' => '🎧'],
            ['name' => 'TVs',          'icon' => '📺'],
            ['name' => 'Accessories',  'icon' => '🔌'],
            ['name' => 'Gaming',       'icon' => '🎮'],
            ['name' => 'Cameras',      'icon' => '📷'],
            ['name' => 'Tablets',      'icon' => '📲'],
        ];

        foreach ($cats as $cat) {
            Category::create(array_merge($cat, ['is_active' => true]));
        }

        $smartphones = Category::where('name', 'Smartphones')->first();
        $laptops     = Category::where('name', 'Laptops')->first();
        $audio       = Category::where('name', 'Audio')->first();
        $tvs         = Category::where('name', 'TVs')->first();
        $gaming      = Category::where('name', 'Gaming')->first();
        $cameras     = Category::where('name', 'Cameras')->first();
        $tablets     = Category::where('name', 'Tablets')->first();
        $accessories = Category::where('name', 'Accessories')->first();

        // ─── Products ────────────────────────────────────────────────────
        $products = [
            [
                'name'              => 'Samsung Galaxy S24 Ultra',
                'short_description' => '6.8" Dynamic AMOLED, 200MP Camera, S Pen Included',
                'description'       => "The Samsung Galaxy S24 Ultra is the pinnacle of smartphone innovation.\n\nFeaturing a stunning 6.8\" Dynamic AMOLED 2X display with QHD+ resolution, the S24 Ultra delivers breathtaking visuals. The new titanium frame adds durability without sacrificing style.\n\n**Camera System:** The 200MP main sensor captures extraordinary detail in any lighting condition, while the 50MP 5x telephoto enables incredible zoom photography.",
                'price'             => 9999.00,
                'old_price'         => 11499.00,
                'stock_quantity'    => 15,
                'category_id'       => $smartphones->id,
                'brand'             => 'Samsung',
                'is_active'         => true,
                'is_featured'       => true,
                'is_new'            => true,
                'is_on_sale'        => true,
                'specifications'    => ['Display' => '6.8" QHD+ AMOLED 120Hz', 'Processor' => 'Snapdragon 8 Gen 3', 'RAM' => '12GB', 'Storage' => '256GB', 'Battery' => '5000mAh', 'Camera' => '200MP + 50MP + 10MP + 12MP', 'OS' => 'Android 14'],
                'features'          => ['Built-in S Pen', '200MP Pro-grade Camera', 'Titanium Frame', '5G Connectivity', 'AI-powered features', 'IP68 Water Resistance'],
            ],
            [
                'name'              => 'iPhone 15 Pro Max',
                'short_description' => '6.7" Super Retina XDR, A17 Pro Chip, Titanium Design',
                'description'       => "The iPhone 15 Pro Max represents the most pro iPhone ever.\n\nBuilt with aerospace-grade titanium, it's the lightest Pro Max yet. The A17 Pro chip with a 6-core GPU makes gaming, AR, and professional photography extraordinary.",
                'price'             => 12499.00,
                'old_price'         => null,
                'stock_quantity'    => 10,
                'category_id'       => $smartphones->id,
                'brand'             => 'Apple',
                'is_active'         => true,
                'is_featured'       => true,
                'is_new'            => true,
                'is_on_sale'        => false,
                'specifications'    => ['Display' => '6.7" Super Retina XDR ProMotion', 'Processor' => 'A17 Pro', 'Storage' => '256GB', 'Camera' => '48MP Main + 12MP Ultra-wide + 12MP 5x Tele', 'Battery' => '4422mAh', 'OS' => 'iOS 17'],
                'features'          => ['Titanium Design', 'Action Button', '5x Optical Zoom', 'USB-C with USB 3', 'ProRes Video Recording', 'Dynamic Island'],
            ],
            [
                'name'              => 'MacBook Pro 14" M3',
                'short_description' => 'Apple M3 Chip, 14" Liquid Retina XDR, 18-hour battery',
                'description'       => "The new MacBook Pro 14\" blows the competition away.\n\nPowered by the M3 chip and featuring Apple's stunning Liquid Retina XDR display, it's perfect for creative professionals who demand the best performance in a portable form factor.",
                'price'             => 15999.00,
                'old_price'         => 17499.00,
                'stock_quantity'    => 8,
                'category_id'       => $laptops->id,
                'brand'             => 'Apple',
                'is_active'         => true,
                'is_featured'       => true,
                'is_new'            => true,
                'is_on_sale'        => true,
                'specifications'    => ['Display' => '14.2" Liquid Retina XDR', 'Processor' => 'Apple M3', 'RAM' => '18GB', 'Storage' => '512GB SSD', 'Battery' => '18hr', 'Ports' => 'MagSafe 3, 3x USB-C, HDMI, SD Card, Headphone Jack'],
                'features'          => ['M3 Chip with 10-core GPU', '120Hz ProMotion', 'Mini-LED display', '1080p FaceTime Camera', 'MagSafe charging', 'Up to 18hr battery'],
            ],
            [
                'name'              => 'Sony WH-1000XM5',
                'short_description' => 'Industry-leading Noise Cancelling, 30hr Battery, Hi-Res Audio',
                'description'       => "Experience audio perfection with the Sony WH-1000XM5.\n\nFeaturing Sony's most advanced noise cancellation processor and four microphones, the WH-1000XM5 eliminates ambient noise like no other headphone can.",
                'price'             => 2499.00,
                'old_price'         => 2999.00,
                'stock_quantity'    => 25,
                'category_id'       => $audio->id,
                'brand'             => 'Sony',
                'is_active'         => true,
                'is_featured'       => true,
                'is_new'            => false,
                'is_on_sale'        => true,
                'specifications'    => ['Driver' => '30mm', 'Frequency' => '4Hz–40kHz', 'Battery Life' => '30 hours', 'Charging' => 'USB-C, 3 min charge = 3 hours', 'Connectivity' => 'Bluetooth 5.2', 'Weight' => '250g'],
                'features'          => ['Industry-leading Noise Cancellation', 'Multipoint connection', 'Speak-to-Chat', 'Adaptive Sound Control', 'Hi-Res Audio', 'Foldable Design'],
            ],
            [
                'name'              => 'Samsung 65" Neo QLED 4K TV',
                'short_description' => '65" Neo QLED, 4K 144Hz, Mini LED, Quantum HDR 2000',
                'description'       => "Transform your living room with the Samsung 65\" Neo QLED 8K TV.\n\nMini LED technology with Quantum Matrix Pro delivers unparalleled contrast and brightness, while the Neural Quantum Processor 4K upscales every frame for stunning detail.",
                'price'             => 18999.00,
                'old_price'         => 22999.00,
                'stock_quantity'    => 5,
                'category_id'       => $tvs->id,
                'brand'             => 'Samsung',
                'is_active'         => true,
                'is_featured'       => true,
                'is_new'            => false,
                'is_on_sale'        => true,
                'specifications'    => ['Screen Size' => '65"', 'Resolution' => '4K Ultra HD', 'Refresh Rate' => '144Hz', 'HDR' => 'Quantum HDR 2000', 'Smart TV' => 'Tizen', 'Ports' => '4x HDMI 2.1, 2x USB'],
                'features'          => ['Mini LED Backlight', 'Quantum Processor 4K', '144Hz Gaming Mode', 'Dolby Atmos', 'FreeSync Premium Pro', 'Smart Calibration'],
            ],
            [
                'name'              => 'PlayStation 5 Console',
                'short_description' => 'Next-gen gaming console, 4K 120fps, 825GB SSD, DualSense Controller',
                'description'       => "Experience the next generation of gaming.\n\nThe PlayStation 5 delivers lightning-fast loading with its custom SSD, deeper immersion with the DualSense wireless controller's haptic feedback and adaptive triggers.",
                'price'             => 5499.00,
                'old_price'         => null,
                'stock_quantity'    => 12,
                'category_id'       => $gaming->id,
                'brand'             => 'Sony',
                'is_active'         => true,
                'is_featured'       => true,
                'is_new'            => false,
                'is_on_sale'        => false,
                'specifications'    => ['CPU' => 'AMD Zen 2, 8 cores at 3.5GHz', 'GPU' => 'AMD RDNA 2, 10.3 TFLOPS', 'RAM' => '16GB GDDR6', 'Storage' => '825GB Custom NVMe SSD', 'Optical' => '4K UHD Blu-ray', 'Resolution' => 'Up to 8K'],
                'features'          => ['DualSense Haptic Feedback', '4K Gaming at 120fps', 'Ray Tracing', '3D Audio', 'Instant Load with SSD', 'Backward Compatible with PS4'],
            ],
            [
                'name'              => 'ASUS ROG Strix G16',
                'short_description' => 'Intel Core i9, RTX 4080, 16" QHD 240Hz Gaming Laptop',
                'description'       => "Crush every game with the ASUS ROG Strix G16.\n\nPowered by Intel's latest Core i9 processor and NVIDIA GeForce RTX 4080, this beast of a gaming laptop delivers console-quality gaming on the go.",
                'price'             => 21999.00,
                'old_price'         => 24999.00,
                'stock_quantity'    => 6,
                'category_id'       => $laptops->id,
                'brand'             => 'ASUS',
                'is_active'         => true,
                'is_featured'       => false,
                'is_new'            => true,
                'is_on_sale'        => true,
                'specifications'    => ['Processor' => 'Intel Core i9-14900HX', 'GPU' => 'NVIDIA RTX 4080 16GB', 'RAM' => '32GB DDR5', 'Storage' => '1TB NVMe SSD', 'Display' => '16" QHD+ 240Hz', 'Battery' => '90Wh'],
                'features'          => ['RTX 4080 Mobile GPU', '240Hz QHD Display', 'ROG Intelligent Cooling', 'RGB Keyboard', 'MUX Switch', 'PCIe 5.0 SSD'],
            ],
            [
                'name'              => 'Xiaomi Redmi Note 13 Pro',
                'short_description' => '6.67" AMOLED 120Hz, 200MP Camera, 67W Fast Charge',
                'description'       => "Incredible value meets flagship features in the Xiaomi Redmi Note 13 Pro.\n\nA 200MP camera, vibrant AMOLED display, and blazing 67W fast charging make this the best mid-range pick of the year.",
                'price'             => 2799.00,
                'old_price'         => 3299.00,
                'stock_quantity'    => 30,
                'category_id'       => $smartphones->id,
                'brand'             => 'Xiaomi',
                'is_active'         => true,
                'is_featured'       => false,
                'is_new'            => true,
                'is_on_sale'        => true,
                'specifications'    => ['Display' => '6.67" AMOLED 1.5K 120Hz', 'Processor' => 'Snapdragon 7s Gen 2', 'RAM' => '8GB', 'Storage' => '256GB', 'Camera' => '200MP + 8MP + 2MP', 'Battery' => '5100mAh, 67W'],
                'features'          => ['200MP OIS Camera', '67W Turbo Charging', 'IP54 Water Resistance', 'In-display Fingerprint', 'Gorilla Glass 5', 'MIUI 14'],
            ],
            [
                'name'              => 'Apple AirPods Pro (2nd Gen)',
                'short_description' => 'Active Noise Cancellation, Adaptive Audio, USB-C, H2 Chip',
                'description'       => "AirPods Pro 2nd generation deliver magical audio experiences.\n\nThe new H2 chip enables 2x more Active Noise Cancellation and Adaptive Audio, which seamlessly blends Transparency mode and ANC based on the sounds of your environment.",
                'price'             => 1999.00,
                'old_price'         => null,
                'stock_quantity'    => 40,
                'category_id'       => $audio->id,
                'brand'             => 'Apple',
                'is_active'         => true,
                'is_featured'       => false,
                'is_new'            => false,
                'is_on_sale'        => false,
                'specifications'    => ['Chip' => 'H2', 'Battery' => '6hr ANC, 30hr with case', 'Connectivity' => 'Bluetooth 5.3', 'Charging' => 'USB-C, MagSafe, Qi2', 'Water Resistance' => 'IPX4'],
                'features'          => ['Active Noise Cancellation', 'Adaptive Transparency', 'Personalized Spatial Audio', 'Touch Controls', 'Find My Network', 'USB-C Charging'],
            ],
            [
                'name'              => 'Canon EOS R50',
                'short_description' => '24.2MP APS-C, 4K Video, Dual Pixel Autofocus, Mirrorless',
                'description'       => "Perfect for creators who want to level up their content.\n\nThe Canon EOS R50 brings professional-level autofocus and video quality in a compact, beginner-friendly mirrorless body. Dual Pixel CMOS AF II tracks subjects with uncanny precision.",
                'price'             => 5999.00,
                'old_price'         => 6999.00,
                'stock_quantity'    => 9,
                'category_id'       => $cameras->id,
                'brand'             => 'Canon',
                'is_active'         => true,
                'is_featured'       => false,
                'is_new'            => false,
                'is_on_sale'        => true,
                'specifications'    => ['Sensor' => '24.2MP APS-C CMOS', 'AF Points' => '651', 'Video' => '4K 30fps', 'Viewfinder' => 'OLED EVF', 'Connectivity' => 'Wi-Fi, Bluetooth', 'Weight' => '375g'],
                'features'          => ['Dual Pixel CMOS AF II', '4K RAW Internal Recording', 'Subject Tracking (People/Animals/Vehicles)', 'Compact & Lightweight', 'Touch LCD', 'Wi-Fi & Bluetooth'],
            ],
            [
                'name'              => 'Samsung Galaxy Tab S9+',
                'short_description' => '12.4" Dynamic AMOLED 2X, Snapdragon 8 Gen 2, S Pen Included',
                'description'       => "Productivity and creativity meet in the Samsung Galaxy Tab S9+.\n\nThe vivid 12.4\" Dynamic AMOLED 2X display with 120Hz refresh rate is perfect for everything from watching movies to sketching with the included S Pen.",
                'price'             => 8499.00,
                'old_price'         => 9999.00,
                'stock_quantity'    => 14,
                'category_id'       => $tablets->id,
                'brand'             => 'Samsung',
                'is_active'         => true,
                'is_featured'       => false,
                'is_new'            => false,
                'is_on_sale'        => true,
                'specifications'    => ['Display' => '12.4" Dynamic AMOLED 2X 120Hz', 'Processor' => 'Snapdragon 8 Gen 2', 'RAM' => '12GB', 'Storage' => '256GB', 'Battery' => '10090mAh, 45W', 'Connectivity' => 'Wi-Fi 6E'],
                'features'          => ['S Pen Included', 'DeX Mode', 'IP68 Water Resistance', 'Dolby Atmos Quad Speakers', 'Samsung DeX Desktop Mode', 'USB-C 3.2 Gen 2'],
            ],
            [
                'name'              => 'Anker 65W GaN Charger',
                'short_description' => 'Compact 3-Port GaN Charger, 65W Total Power, USB-C & USB-A',
                'description'       => "Charge all your devices fast with Anker's compact GaN charger.\n\nWith 65W total power output across 3 ports (2x USB-C + 1x USB-A), this GaN charger is 40% smaller than traditional chargers while delivering faster charging speeds.",
                'price'             => 449.00,
                'old_price'         => null,
                'stock_quantity'    => 60,
                'category_id'       => $accessories->id,
                'brand'             => 'Anker',
                'is_active'         => true,
                'is_featured'       => false,
                'is_new'            => false,
                'is_on_sale'        => false,
                'specifications'    => ['Total Power' => '65W', 'Ports' => '2x USB-C + 1x USB-A', 'Size' => 'Ultra-compact', 'Technology' => 'GaN II', 'Compatibility' => 'Universal'],
                'features'          => ['65W USB-C Fast Charging', 'GaN Technology', '40% Smaller than Standard Chargers', 'Intelligent Power Distribution', 'Multi-device Charging', 'Travel-friendly'],
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // ─── Sample Orders ────────────────────────────────────────────────
        $sampleProducts = Product::take(3)->get();

        $ordersData = [
            [
                'customer_name'    => 'Youssef Ait Brahim',
                'customer_email'   => 'youssef@example.com',
                'customer_phone'   => '+212 6 12-345-678',
                'shipping_address' => ['address' => 'Rue Hassan II, N° 14', 'city' => 'Akka', 'postal_code' => '85450'],
                'notes'            => null,
                'payment_method'   => 'cod',
                'subtotal'         => 9999.00,
                'shipping_cost'    => 0.00,
                'total_amount'     => 9999.00,
                'payment_status'   => 'pending',
                'order_status'     => 'new',
                'items' => [
                    ['product_id' => $sampleProducts[0]->id ?? null, 'name' => $sampleProducts[0]->name ?? 'Product', 'price' => 9999.00, 'quantity' => 1, 'subtotal' => 9999.00],
                ],
            ],
            [
                'customer_name'    => 'Fatima Zahra Essalem',
                'customer_email'   => 'fatima@example.com',
                'customer_phone'   => '+212 6 98-765-432',
                'shipping_address' => ['address' => 'Avenue Mohammed V, Appt 5', 'city' => 'Tiznit', 'postal_code' => '85000'],
                'notes'            => 'Please deliver in the morning',
                'payment_method'   => 'cod',
                'subtotal'         => 17498.00,
                'shipping_cost'    => 0.00,
                'total_amount'     => 17498.00,
                'payment_status'   => 'paid',
                'order_status'     => 'processing',
                'items' => [
                    ['product_id' => $sampleProducts[1]->id ?? null, 'name' => $sampleProducts[1]->name ?? 'Product', 'price' => 15999.00, 'quantity' => 1, 'subtotal' => 15999.00],
                    ['product_id' => $sampleProducts[2]->id ?? null, 'name' => $sampleProducts[2]->name ?? 'Product', 'price' => 1499.00, 'quantity' => 1, 'subtotal' => 1499.00],
                ],
            ],
            [
                'customer_name'    => 'Omar Benali',
                'customer_email'   => 'omar@example.com',
                'customer_phone'   => '+212 6 55-111-222',
                'shipping_address' => ['address' => 'Quartier Al Massira, Lot 8', 'city' => 'Guelmim', 'postal_code' => '81000'],
                'notes'            => null,
                'payment_method'   => 'cod',
                'subtotal'         => 5499.00,
                'shipping_cost'    => 30.00,
                'total_amount'     => 5529.00,
                'payment_status'   => 'pending',
                'order_status'     => 'shipped',
                'tracking_number'  => 'MA123456789CN',
                'items' => [
                    ['product_id' => $sampleProducts[0]->id ?? null, 'name' => 'PlayStation 5', 'price' => 5499.00, 'quantity' => 1, 'subtotal' => 5499.00],
                ],
            ],
            [
                'customer_name'    => 'Amina Tazi',
                'customer_email'   => 'amina@example.com',
                'customer_phone'   => '+212 6 77-888-999',
                'shipping_address' => ['address' => 'Rue des Orangers N°3', 'city' => 'Agadir', 'postal_code' => '80000'],
                'notes'            => null,
                'payment_method'   => 'cod',
                'subtotal'         => 3998.00,
                'shipping_cost'    => 0.00,
                'total_amount'     => 3998.00,
                'payment_status'   => 'paid',
                'order_status'     => 'delivered',
                'items' => [
                    ['product_id' => null, 'name' => 'Sony WH-1000XM5', 'price' => 2499.00, 'quantity' => 1, 'subtotal' => 2499.00],
                    ['product_id' => null, 'name' => 'Anker 65W GaN Charger', 'price' => 449.00, 'quantity' => 1, 'subtotal' => 449.00],
                    ['product_id' => null, 'name' => 'Apple AirPods Pro (2nd Gen)', 'price' => 1050.00, 'quantity' => 1, 'subtotal' => 1050.00],
                ],
            ],
        ];

        foreach ($ordersData as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);
            Order::create(array_merge($orderData, ['items' => $items]));
        }
    }
}
