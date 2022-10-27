-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 14 2021 г., 13:52
-- Версия сервера: 8.0.12
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `charity`
--

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Name2222'),
(2, 'Name 2'),
(3, 'hgfhg'),
(4, 'hgfhg');

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT '0',
  `data` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1630053231),
('m140209_132017_init', 1630053236),
('m140403_174025_create_account_table', 1630053236),
('m140504_113157_update_tables', 1630053237),
('m140504_130429_create_token_table', 1630053237),
('m140830_171933_fix_ip_field', 1630053237),
('m140830_172703_change_account_table_name', 1630053238),
('m141217_232437_simplecms_init', 1630407066),
('m141222_110026_update_ip_field', 1630053238),
('m141222_135246_alter_username_length', 1630053238),
('m150614_103145_update_social_account_table', 1630053238),
('m150623_212711_fix_username_notnull', 1630053238),
('m150725_130000_simplecms_newpagefield', 1630407066),
('m151114_123000_simplecms_newpagefield', 1630407067),
('m151218_234654_add_timezone_to_profile', 1630053238),
('m160929_103127_add_last_login_at_to_user_table', 1630053239),
('m170312_230000_simplecms_teaserfields', 1630407067);

-- --------------------------------------------------------

--
-- Структура таблицы `organization`
--

CREATE TABLE `organization` (
  `id` int(11) NOT NULL,
  `name` mediumtext,
  `url` mediumtext,
  `category_id` int(11) DEFAULT NULL,
  `description` longtext,
  `email` mediumtext,
  `active` tinyint(4) DEFAULT '1',
  `lang_id` int(11) DEFAULT NULL,
  `logo` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `organization`
--

INSERT INTO `organization` (`id`, `name`, `url`, `category_id`, `description`, `email`, `active`, `lang_id`, `logo`) VALUES
(1, 'name', 'http://google.com', 3, 'Some desc', 'trum@tum.tum', 1, NULL, NULL),
(3, 'name', 'http://google.com', 3, 'Some desc', 'trum@tum.tum', 1, NULL, NULL),
(4, 'name', 'http://google.com', 3, 'Some desc', 'trum@tum.tum', 1, NULL, NULL),
(5, 'name', 'http://google.com', 1, 'Some desc', 'trum@tum.tum', 1, NULL, NULL),
(6, 'name', 'http://google.com', 4, 'Some desc', 'trum@tum.tum', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `title` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `seo` json DEFAULT NULL,
  `active` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(150) NOT NULL,
  `avatar` longtext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `restore` tinyint(4) DEFAULT '0',
  `restore_token` varchar(50) DEFAULT NULL,
  `user_role` int(2) NOT NULL DEFAULT '1',
  `status` int(11) DEFAULT '1',
  `sign_up_token` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `active` int(11) DEFAULT '0',
  `news` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `avatar`, `created_date`, `updated_date`, `restore`, `restore_token`, `user_role`, `status`, `sign_up_token`, `active`, `news`) VALUES
(15, 'Roman R', NULL, 'dosaaf25@gmail.com', '$2y$13$s.Jmk4d5neCr9txd9gNae.RjL17x1e5XfoBbrWbRJ3PXMutxIR4pC', NULL, '2021-09-03 12:48:45', '2021-09-03 12:48:45', 0, NULL, 1, 1, '', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`id`, `name`, `permissions`, `status`) VALUES
(1, 'Admin', NULL, 1),
(2, 'User', NULL, 1),
(3, 'Premium', NULL, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_organization_category` (`category_id`);

--
-- Индексы таблицы `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_role` (`user_role`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `organization`
--
ALTER TABLE `organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `organization`
--
ALTER TABLE `organization`
  ADD CONSTRAINT `FK_organization_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
