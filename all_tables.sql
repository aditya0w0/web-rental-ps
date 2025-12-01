
   INFO  Running migrations.  

  2024_01_01_000001_create_order_items_table .........................................................................  
  ⇂ create table `order_items` (`id` bigint unsigned not null auto_increment primary key, `order_id` bigint unsigned not null, `accessory_id` bigint unsigned not null, `quantity` int not null, `price` decimal(10, 2) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `order_items` add constraint `order_items_order_id_foreign` foreign key (`order_id`) references `orders` (`id`) on delete cascade  
  ⇂ alter table `order_items` add constraint `order_items_accessory_id_foreign` foreign key (`accessory_id`) references `accessories` (`id`) on delete cascade  
  2024_01_01_000001_create_playstation_types_table ...................................................................  
  ⇂ create table `playstation_types` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `description` varchar(255) null, `rental_price_per_hour` decimal(10, 2) not null, `rental_price_per_day` decimal(10, 2) not null, `image` varchar(255) null, `is_active` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  2024_01_01_000002_create_carts_table ...............................................................................  
  ⇂ create table `carts` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `carts` add constraint `carts_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade  
  2024_01_01_000002_create_playstation_units_table ...................................................................  
  ⇂ create table `playstation_units` (`id` bigint unsigned not null auto_increment primary key, `playstation_type_id` bigint unsigned not null, `unit_code` varchar(255) not null, `serial_number` varchar(255) not null, `status` enum('available', 'rented', 'maintenance') not null default 'available', `condition_notes` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `playstation_units` add constraint `playstation_units_playstation_type_id_foreign` foreign key (`playstation_type_id`) references `playstation_types` (`id`) on delete cascade  
  ⇂ alter table `playstation_units` add unique `playstation_units_unit_code_unique`(`unit_code`)  
  ⇂ alter table `playstation_units` add unique `playstation_units_serial_number_unique`(`serial_number`)  
  2024_01_01_000003_create_accessories_table .........................................................................  
  ⇂ create table `accessories` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `category` varchar(255) not null, `description` text null, `price` decimal(10, 2) not null, `stock` int not null, `image` varchar(255) null, `brand` varchar(255) null, `is_active` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  2024_01_01_000003_create_cart_items_table ..........................................................................  
  ⇂ create table `cart_items` (`id` bigint unsigned not null auto_increment primary key, `cart_id` bigint unsigned not null, `accessory_id` bigint unsigned not null, `quantity` int not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `cart_items` add constraint `cart_items_cart_id_foreign` foreign key (`cart_id`) references `carts` (`id`) on delete cascade  
  ⇂ alter table `cart_items` add constraint `cart_items_accessory_id_foreign` foreign key (`accessory_id`) references `accessories` (`id`) on delete cascade  
  2024_01_01_000004_create_rentals_table .............................................................................  
  ⇂ create table `rentals` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `playstation_unit_id` bigint unsigned not null, `playstation_type_id` bigint unsigned not null, `start_time` datetime not null, `end_time` datetime not null, `duration_type` enum('hour', 'day') not null, `duration_value` int not null, `total_price` decimal(10, 2) not null, `status` enum('pending', 'confirmed', 'active', 'completed', 'cancelled') not null default 'pending', `pickup_method` enum('store_pickup', 'delivery') not null, `delivery_address` text null, `phone_number` varchar(255) not null, `notes` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `rentals` add constraint `rentals_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade  
  ⇂ alter table `rentals` add constraint `rentals_playstation_unit_id_foreign` foreign key (`playstation_unit_id`) references `playstation_units` (`id`) on delete cascade  
  ⇂ alter table `rentals` add constraint `rentals_playstation_type_id_foreign` foreign key (`playstation_type_id`) references `playstation_types` (`id`) on delete cascade  
  2024_01_01_000005_create_transactions_table ........................................................................  
  ⇂ create table `transactions` (`id` bigint unsigned not null auto_increment primary key, `transaction_code` varchar(255) not null, `user_id` bigint unsigned not null, `type` enum('rental', 'accessory_purchase', 'combined') not null, `total_amount` decimal(10, 2) not null, `payment_method` enum('bank_transfer', 'qris') not null, `payment_status` enum('pending', 'paid', 'failed', 'refunded') not null default 'pending', `payment_proof` varchar(255) null, `payment_date` datetime null, `pickup_method` enum('store_pickup', 'delivery') not null, `delivery_address` text null, `notes` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `transactions` add constraint `transactions_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade  
  ⇂ alter table `transactions` add unique `transactions_transaction_code_unique`(`transaction_code`)  
  2024_01_01_000006_create_transaction_items_table ...................................................................  
  ⇂ create table `transaction_items` (`id` bigint unsigned not null auto_increment primary key, `transaction_id` bigint unsigned not null, `item_type` enum('rental', 'accessory') not null, `item_id` bigint unsigned not null, `item_name` varchar(255) not null, `quantity` int not null, `price` decimal(10, 2) not null, `subtotal` decimal(10, 2) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `transaction_items` add constraint `transaction_items_transaction_id_foreign` foreign key (`transaction_id`) references `transactions` (`id`) on delete cascade  
  2024_01_01_000007_create_cart_items_table ..........................................................................  
  ⇂ create table `cart_items` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `accessory_id` bigint unsigned not null, `quantity` int not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_0900_ai_ci'  
  ⇂ alter table `cart_items` add constraint `cart_items_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade  
  ⇂ alter table `cart_items` add constraint `cart_items_accessory_id_foreign` foreign key (`accessory_id`) references `accessories` (`id`) on delete cascade  
  2024_01_01_000008_add_role_to_users_table ..........................................................................  
  ⇂ alter table `users` add `role` enum('admin', 'customer') not null default 'customer' after `email`  
  ⇂ alter table `users` add `phone` varchar(255) null after `role`  
  ⇂ alter table `users` add `address` text null after `phone`  

