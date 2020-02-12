# diary
```
--
-- Структура таблицы `diary`
--

CREATE TABLE `diary` (
  `id` int(11) NOT NULL,
  `situation` text CHARACTER SET utf16 NOT NULL,
  `thoughts` text NOT NULL,
  `alternative` text NOT NULL,
  `conclusion` text NOT NULL,
  `date` date NOT NULL,
  `emotion` varchar(30) NOT NULL,
  `emotion_level` int(11) NOT NULL,
  `tress` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```
