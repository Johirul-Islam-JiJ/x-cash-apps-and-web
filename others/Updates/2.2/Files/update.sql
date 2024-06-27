ALTER TABLE `api_payments` CHANGE `site_logo` `site_logo` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
CREATE TABLE `plugins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` double NOT NULL DEFAULT 1,
  `meta_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plugin_for` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `plugins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;