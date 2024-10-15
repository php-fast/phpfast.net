-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th10 07, 2024 lúc 04:11 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phpfast.net`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fast_languages`
--

CREATE TABLE `fast_languages` (
  `id` int UNSIGNED NOT NULL,
  `code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `fast_languages`
--

INSERT INTO `fast_languages` (`id`, `code`, `name`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English (US)', 1, 'active', '2024-10-02 04:26:52', '2024-10-02 04:49:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fast_posttype`
--

CREATE TABLE `fast_posttype` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fields` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fast_users`
--

CREATE TABLE `fast_users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `role` enum('admin','moderator','author','member') COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` json DEFAULT NULL,
  `optional` json DEFAULT NULL,
  `status` enum('active','inactive','banned') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `fast_users`
--

INSERT INTO `fast_users` (`id`, `username`, `email`, `password`, `fullname`, `avatar`, `role`, `permissions`, `optional`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$jJzcVXtMuqC3LKSjtX2I0ulknNZCZmJuP8lIlKBq0vaTWAJYFZamu', 'Admin Full', 'avatar.jpg', 'admin', '{\"Backend\\\\Posts\": [\"view\", \"edit\", \"create\", \"delete\"], \"Backend\\\\Users\": [\"view\", \"edit\", \"create\", \"delete\"], \"Backend\\\\Options\": [\"view\", \"edit\"], \"Backend\\\\Dashboard\": [\"view\", \"edit\", \"delete\"]}', NULL, 'active', '2024-09-30 10:08:16', '2024-10-02 03:18:15'),
(17, 'phpswoole', 'phpswoole@gmail.com', '$2y$10$57vXANStqSlSrYhG9K0RWO/kbrjshBHr1gbg1wdhFK6sN4Eqnp6z2', 'phpswoole@gmail.com', '', 'admin', '{\"Backend\\\\Posts\": [\"index\", \"view\", \"edit\", \"create\", \"delete\"], \"Backend\\\\Users\": [\"view\", \"edit\", \"create\", \"delete\"], \"Backend\\\\Options\": [\"view\", \"edit\"], \"Backend\\\\Dashboard\": [\"index\", \"edit\", \"delete\"]}', NULL, 'active', '2024-10-02 03:17:57', '2024-10-02 03:31:37');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `fast_languages`
--
ALTER TABLE `fast_languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `fast_posttype`
--
ALTER TABLE `fast_posttype`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `fast_users`
--
ALTER TABLE `fast_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `fast_languages`
--
ALTER TABLE `fast_languages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `fast_posttype`
--
ALTER TABLE `fast_posttype`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `fast_users`
--
ALTER TABLE `fast_users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
