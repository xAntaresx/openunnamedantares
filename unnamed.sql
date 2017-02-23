-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 22. Feb 2017 um 23:15
-- Server-Version: 5.6.35
-- PHP-Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `58256m47358_1`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_battle`
--

CREATE TABLE `alk_battle` (
  `id` int(11) NOT NULL,
  `battleid` text NOT NULL,
  `ownid` text NOT NULL,
  `enemyid` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `battletext` text NOT NULL,
  `hitpoints` int(11) NOT NULL DEFAULT '1',
  `mana` int(11) NOT NULL DEFAULT '0',
  `gotloot` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_buff`
--

CREATE TABLE `alk_buff` (
  `id` int(11) NOT NULL,
  `userid` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `rounds` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_buildings`
--

CREATE TABLE `alk_buildings` (
  `id` int(11) NOT NULL,
  `file` varchar(64) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `img` varchar(64) NOT NULL,
  `inventory` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `alk_buildings`
--

INSERT INTO `alk_buildings` (`id`, `file`, `name`, `description`, `img`, `inventory`) VALUES
(1, 'vendor.php', 'Schmied', 'Ein Schmied, nicht mehr, nicht weniger.', 'house1.png', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_chat`
--

CREATE TABLE `alk_chat` (
  `id` int(64) NOT NULL,
  `place` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `alk_chat`
--

INSERT INTO `alk_chat` (`id`, `place`, `user`, `message`, `date`) VALUES
(41, '500:500', 1, 'oho', '2016-12-16 23:23:03'),
(40, '500:500', 1, 'test', '2016-12-16 23:22:57'),
(42, '500:500', 1, ':)', '2016-12-16 23:23:08');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_inventory`
--

CREATE TABLE `alk_inventory` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `uniquename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `value1` text NOT NULL,
  `value2` text NOT NULL,
  `pri_skill` text NOT NULL,
  `sec_skill` varchar(255) NOT NULL,
  `gold` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `equiped` tinyint(1) NOT NULL DEFAULT '0',
  `image` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_items`
--

CREATE TABLE `alk_items` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `shoppos` text NOT NULL,
  `uniquename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `gold` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `value1` text NOT NULL,
  `value2` text NOT NULL,
  `pri_skill` varchar(255) NOT NULL,
  `sec_skill` varchar(255) NOT NULL,
  `image` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `alk_items`
--

INSERT INTO `alk_items` (`id`, `type`, `shoppos`, `uniquename`, `name`, `description`, `gold`, `weight`, `exp`, `value1`, `value2`, `pri_skill`, `sec_skill`, `image`) VALUES
(1, 'weapon', '500:500', 'rustysword', 'rostiges Schwert', '', 5, 0, 0, '1W4', '3', 'sword', 'tactic', ''),
(2, 'weapon', '500:500', 'bluntaxe', 'stumpfe Axt', '', 5, 0, 0, '1W6', '2', 'axe', 'tactic', ''),
(3, 'weapon', '500:500', 'gnarledstaff', 'knorriger Stock', '', 5, 0, 0, '1W6', '1', 'staff', 'tactic', ''),
(4, 'nahrung-roh', '', 'bushmeat', 'Wildfleisch (roh)', '', 1, 1, 0, '', '', '', '', ''),
(5, 'helmet', '500:500', 'oldleatherhelmet', 'alter Lederhelm', '', 5, 2, 0, '1', '', '', '', ''),
(6, 'nahrung', '', 'bramble', 'Brombeere', '', 1, 1, 1, '20', '5', 'wilderness', 'botany', ''),
(7, 'nahrung-unbekannt', '', 'unknownberry', 'unbekannte Beere', '', 1, 1, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_map`
--

CREATE TABLE `alk_map` (
  `id` int(32) NOT NULL,
  `x` int(11) NOT NULL DEFAULT '0',
  `y` int(11) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  `description` text NOT NULL,
  `img` text NOT NULL,
  `backimg` text NOT NULL,
  `file` text NOT NULL,
  `block` tinyint(1) NOT NULL DEFAULT '0',
  `monster` text NOT NULL,
  `buildings` text NOT NULL,
  `plants` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `alk_map`
--

INSERT INTO `alk_map` (`id`, `x`, `y`, `name`, `description`, `img`, `backimg`, `file`, `block`, `monster`, `buildings`, `plants`) VALUES
(539, 500, 500, 'Mondbruch', '', 'mondbruch', 'grass', 'town.php', 0, '', '1', ''),
(540, 497, 497, 'Eichenhain', '', 'forest', 'forest', 'forest.php', 0, 'rabbit,hart', '', '6'),
(541, 498, 500, '', '', '', 'water', '', 1, '', '', ''),
(542, 498, 499, '', '', '', 'water', '', 1, '', '', ''),
(543, 499, 500, '', '', '', 'desert', '', 0, '', '', ''),
(544, 499, 499, '', '', '', 'desert', '', 0, '', '', ''),
(545, 497, 499, '', '', '', 'desert', '', 0, '', '', ''),
(546, 497, 498, '', '', '', 'forest', '', 0, '', '', ''),
(547, 498, 498, '', '', '', 'desert', '', 0, '', '', ''),
(548, 499, 498, '', '', '', 'grass', '', 0, '', '', ''),
(549, 497, 500, '', '', '', 'water', '', 1, '', '', ''),
(550, 497, 501, '', '', '', 'water', '', 1, '', '', ''),
(551, 498, 501, '', '', '', 'water', '', 1, '', '', ''),
(552, 499, 501, '', '', '', 'desert', '', 0, '', '', ''),
(553, 497, 502, '', '', '', 'water', '', 1, '', '', ''),
(554, 498, 502, '', '', '', 'desert', '', 0, '', '', ''),
(555, 499, 502, '', '', '', 'grass', '', 0, '', '', ''),
(556, 500, 499, '', '', '', 'grass', '', 0, '', '', ''),
(557, 500, 501, '', '', '', 'grass', '', 0, '', '', ''),
(558, 497, 503, '', '', '', 'desert', '', 0, '', '', ''),
(559, 498, 503, '', '', '', 'desert', '', 0, '', '', ''),
(560, 498, 497, '', '', '', 'forest', '', 0, '', '', ''),
(561, 499, 497, '', '', '', 'grass', '', 0, '', '', ''),
(562, 496, 503, '', '', '', 'water', '', 1, '', '', ''),
(563, 496, 502, '', '', '', 'water', '', 1, '', '', ''),
(564, 496, 504, '', '', '', 'water', '', 1, '', '', ''),
(565, 497, 505, '', '', '', 'water', '', 1, '', '', ''),
(566, 497, 504, '', '', '', 'desert', '', 0, '', '', ''),
(567, 498, 504, '', '', '', 'desert', '', 0, '', '', ''),
(568, 496, 505, '', '', '', 'water', '', 1, '', '', ''),
(569, 499, 504, '', '', '', 'desert', '', 0, '', '', ''),
(570, 499, 503, '', '', '', 'grass', '', 0, '', '', ''),
(571, 500, 502, '', '', '', 'grass', '', 0, '', '', ''),
(572, 500, 503, '', '', '', 'grass', '', 0, '', '', ''),
(573, 498, 505, '', '', '', 'water', '', 1, '', '', ''),
(574, 499, 506, '', '', '', 'water', '', 1, '', '', ''),
(575, 499, 505, '', '', '', 'desert', '', 0, '', '', ''),
(576, 500, 505, '', '', '', 'desert', '', 0, '', '', ''),
(577, 500, 504, '', '', '', 'desert', '', 0, '', '', ''),
(578, 498, 506, '', '', '', 'water', '', 1, '', '', ''),
(579, 501, 506, '', '', '', 'water', '', 1, '', '', ''),
(580, 501, 505, '', '', '', 'water', '', 1, '', '', ''),
(581, 500, 506, '', '', '', 'water', '', 1, '', '', ''),
(582, 501, 504, '', '', '', 'desert', '', 0, '', '', ''),
(583, 501, 503, '', '', '', 'hill', '', 1, '', '', ''),
(584, 501, 502, '', '', '', 'hill', '', 1, '', '', ''),
(585, 501, 501, '', '', '', 'grass', '', 0, '', '', ''),
(586, 501, 500, '', '', '', 'grass', '', 0, '', '', ''),
(587, 501, 499, '', '', '', 'grass', '', 0, '', '', ''),
(588, 500, 498, '', '', '', 'grass', '', 0, '', '', ''),
(589, 496, 499, '', '', '', 'desert', '', 0, '', '', ''),
(590, 495, 500, '', '', '', 'water', '', 1, '', '', ''),
(591, 495, 499, '', '', '', 'water', '', 1, '', '', ''),
(592, 496, 498, '', '', '', 'forest', '', 0, '', '', ''),
(593, 496, 500, '', '', '', 'water', '', 1, '', '', ''),
(594, 496, 501, '', '', '', 'water', '', 1, '', '', ''),
(595, 495, 498, '', '', '', 'desert', '', 0, '', '', ''),
(596, 496, 497, '', '', '', 'forest', '', 0, '', '', ''),
(597, 496, 496, '', '', '', 'forest', '', 0, '', '', ''),
(598, 498, 496, '', '', '', 'forest', '', 0, '', '', ''),
(599, 497, 496, '', '', '', 'forest', '', 0, '', '', ''),
(600, 495, 497, '', '', '', 'forest', '', 0, '', '', ''),
(601, 495, 496, '', '', '', 'forest', '', 0, '', '', ''),
(602, 500, 497, '', '', '', 'grass', '', 0, '', '', ''),
(603, 499, 496, '', '', '', 'grass', '', 0, '', '', ''),
(604, 500, 496, '', '', '', 'grass', '', 0, '', '', ''),
(605, 501, 497, '', '', '', 'grass', '', 0, '', '', ''),
(614, 501, 498, '', '', '', 'grass', '', 0, '', '', ''),
(606, 502, 504, '', '', '', 'hill', '', 1, '', '', ''),
(607, 502, 505, '', '', '', 'water', '', 1, '', '', ''),
(608, 502, 503, '', '', '', 'hill', '', 1, '', '', ''),
(609, 502, 502, '', '', '', 'hill', '', 1, '', '', ''),
(610, 503, 502, '', '', '', 'hill', '', 1, '', '', ''),
(611, 502, 501, '', '', '', 'hill', '', 1, '', '', ''),
(612, 495, 501, '', '', '', 'water', '', 1, '', '', ''),
(613, 495, 502, '', '', '', 'water', '', 1, '', '', ''),
(615, 497, 495, '', '', '', 'forest', '', 0, '', '', ''),
(616, 496, 495, '', '', '', 'forest', '', 0, '', '', ''),
(617, 495, 495, '', '', '', 'forest', '', 0, '', '', ''),
(618, 502, 497, '', '', '', 'hill', '', 1, '', '', ''),
(619, 502, 498, '', '', '', 'hill', '', 1, '', '', ''),
(620, 502, 499, '', '', '', 'hill', '', 1, '', '', ''),
(621, 503, 500, '', '', '', 'hill', '', 1, '', '', ''),
(622, 503, 499, '', '', '', 'hill', '', 1, '', '', ''),
(623, 502, 500, '', '', '', 'hill', '', 1, '', '', ''),
(624, 503, 501, '', '', '', 'hill', '', 1, '', '', ''),
(625, 503, 498, '', '', '', 'hill', '', 1, '', '', ''),
(626, 503, 497, '', '', '', 'hill', '', 1, '', '', ''),
(627, 503, 503, '', '', '', 'hill', '', 1, '', '', ''),
(628, 498, 495, '', '', '', 'grass', '', 1, '', '', ''),
(629, 499, 495, '', '', '', 'grass', '', 1, '', '', ''),
(630, 500, 495, '', '', '', 'grass', '', 1, '', '', ''),
(631, 501, 496, '', '', '', 'hill', '', 1, '', '', ''),
(632, 502, 496, '', '', '', 'hill', '', 1, '', '', ''),
(633, 503, 496, '', '', '', 'hill', '', 1, '', '', ''),
(634, 502, 495, '', '', '', 'hill', '', 1, '', '', ''),
(635, 503, 495, '', '', '', 'hill', '', 1, '', '', ''),
(636, 504, 494, '', '', '', 'hill', '', 1, '', '', ''),
(637, 504, 495, '', '', '', 'hill', '', 1, '', '', ''),
(638, 501, 495, '', '', '', 'grass', '', 1, '', '', ''),
(639, 502, 494, '', '', '', 'grass', '', 1, '', '', ''),
(640, 503, 494, '', '', '', 'grass', '', 1, '', '', ''),
(641, 505, 494, '', '', '', 'hill', '', 1, '', '', ''),
(642, 506, 494, '', '', '', 'hill', '', 1, '', '', ''),
(643, 506, 493, '', '', '', 'hill', '', 1, '', '', ''),
(644, 506, 495, '', '', '', 'hill', '', 1, '', '', ''),
(645, 507, 494, '', '', '', 'hill', '', 1, '', '', ''),
(646, 507, 495, '', '', '', 'hill', '', 1, '', '', ''),
(647, 505, 495, '', '', '', 'desert', '', 1, '', '', ''),
(648, 504, 496, '', '', '', 'desert', '', 1, '', '', ''),
(649, 505, 496, '', '', '', 'desert', '', 1, '', '', ''),
(650, 504, 497, '', '', '', 'desert', '', 1, '', '', ''),
(651, 504, 498, '', '', '', 'desert', '', 1, '', '', ''),
(652, 506, 496, '', '', '', 'desert', '', 1, '', '', ''),
(653, 505, 497, '', '', '', 'desert', '', 1, '', '', ''),
(654, 504, 499, '', '', '', 'desert', '', 1, '', '', ''),
(655, 504, 500, '', '', '', 'desert', '', 1, '', '', ''),
(656, 504, 501, '', '', '', 'desert', '', 1, '', '', ''),
(657, 494, 498, '', '', '', 'desert', '', 1, '', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_messages`
--

CREATE TABLE `alk_messages` (
  `id` int(32) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `receiver` int(11) NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `date` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_monster`
--

CREATE TABLE `alk_monster` (
  `id` int(11) NOT NULL,
  `uniquename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hitpoints` int(11) NOT NULL,
  `mana` int(11) NOT NULL,
  `weaponname` text CHARACTER SET utf32 COLLATE utf32_bin NOT NULL,
  `damage` text NOT NULL,
  `armor` int(11) NOT NULL,
  `dexterity` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  `gold` int(11) NOT NULL,
  `loot` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `alk_monster`
--

INSERT INTO `alk_monster` (`id`, `uniquename`, `name`, `hitpoints`, `mana`, `weaponname`, `damage`, `armor`, `dexterity`, `exp`, `gold`, `loot`) VALUES
(1, 'rabbit', 'Kaninchen', 10, 0, 'Zähne', '1W2', 0, 5, 2, 0, '1:bushmeat:1:2'),
(2, 'hart', 'Hirsch', 25, 0, 'Geweih', '1W4', 0, 10, 5, 0, '1:bushmeat:1:2,1:bushmeat:1:2');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_skills`
--

CREATE TABLE `alk_skills` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `sword` int(11) NOT NULL DEFAULT '20',
  `axe` int(11) NOT NULL DEFAULT '20',
  `staff` int(11) NOT NULL DEFAULT '20',
  `tactics` int(11) NOT NULL DEFAULT '0',
  `spellcasting` int(11) NOT NULL DEFAULT '0',
  `wilderness` int(11) NOT NULL DEFAULT '0',
  `botany` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `alk_skills`
--

INSERT INTO `alk_skills` (`id`, `userid`, `sword`, `axe`, `staff`, `tactics`, `spellcasting`, `wilderness`, `botany`) VALUES
(1, 1, 20, 20, 21, 0, 14, 100, 100),
(4, 2, 20, 20, 20, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alk_user`
--

CREATE TABLE `alk_user` (
  `id` int(10) NOT NULL,
  `su` tinyint(1) NOT NULL DEFAULT '0',
  `user` varchar(200) NOT NULL DEFAULT '',
  `passwort` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `lastlogin` text NOT NULL,
  `lang` varchar(4) NOT NULL DEFAULT 'en',
  `avatar` text NOT NULL,
  `classname` varchar(255) NOT NULL,
  `profiltext` text NOT NULL,
  `hitpoints` int(11) NOT NULL DEFAULT '10',
  `mana` int(11) NOT NULL DEFAULT '0',
  `regdate` text NOT NULL,
  `ap` int(11) NOT NULL DEFAULT '0',
  `apdate` text NOT NULL,
  `strenght` int(11) NOT NULL DEFAULT '1',
  `constitution` int(11) NOT NULL DEFAULT '1',
  `dexterity` int(11) NOT NULL DEFAULT '1',
  `intelligence` int(11) NOT NULL DEFAULT '1',
  `willpower` int(11) NOT NULL DEFAULT '1',
  `speed` int(11) NOT NULL DEFAULT '1',
  `exp` int(11) NOT NULL DEFAULT '0',
  `gold` int(32) NOT NULL DEFAULT '5',
  `inbank` int(32) NOT NULL DEFAULT '0',
  `pos` varchar(11) NOT NULL DEFAULT '500:500',
  `endpos` varchar(11) NOT NULL,
  `movedate` text NOT NULL,
  `magicknowledge` text NOT NULL,
  `allowednavs` text NOT NULL,
  `battleid` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `alk_user`
--

INSERT INTO `alk_user` (`id`, `su`, `user`, `passwort`, `email`, `lastlogin`, `lang`, `avatar`, `classname`, `profiltext`, `hitpoints`, `mana`, `regdate`, `ap`, `apdate`, `strenght`, `constitution`, `dexterity`, `intelligence`, `willpower`, `speed`, `exp`, `gold`, `inbank`, `pos`, `endpos`, `movedate`, `magicknowledge`, `allowednavs`, `battleid`) VALUES
(1, 2, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@email.de', '161217023010', 'de', 'https://www.alkhemeia.de/alkhemeia', 'Admin', '', 10, 10, '161220091441', 6, '161220091340', 1, 1, 1, 1, 1, 1, 50, 5, 0, '497:497', '497:497', '1612170060', '', ',forest.php,map.php,chat.php,charakter.php,settings.php,profile.php,currentposition.php,move.php,messages.php,toplist.php,battle.php,forest.php', ''),
(2, 0, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'user@email.de', '161220091323', 'de', '', '', '', 10, 4, '161220091441', 2, '161220091340', 1, 1, 1, 1, 1, 1, 0, 5, 0, '500:500', '500:500', '1612200905', '', ',settings.php,map.php,chat.php,charakter.php,settings.php,profile.php,currentposition.php,move.php,messages.php,toplist.php,battle.php,town.php', '');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `alk_battle`
--
ALTER TABLE `alk_battle`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_buff`
--
ALTER TABLE `alk_buff`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_buildings`
--
ALTER TABLE `alk_buildings`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_chat`
--
ALTER TABLE `alk_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_inventory`
--
ALTER TABLE `alk_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_items`
--
ALTER TABLE `alk_items`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_map`
--
ALTER TABLE `alk_map`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_messages`
--
ALTER TABLE `alk_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_monster`
--
ALTER TABLE `alk_monster`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_skills`
--
ALTER TABLE `alk_skills`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `alk_user`
--
ALTER TABLE `alk_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `alk_battle`
--
ALTER TABLE `alk_battle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;
--
-- AUTO_INCREMENT für Tabelle `alk_buff`
--
ALTER TABLE `alk_buff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `alk_buildings`
--
ALTER TABLE `alk_buildings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `alk_chat`
--
ALTER TABLE `alk_chat`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT für Tabelle `alk_inventory`
--
ALTER TABLE `alk_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `alk_items`
--
ALTER TABLE `alk_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `alk_map`
--
ALTER TABLE `alk_map`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=658;
--
-- AUTO_INCREMENT für Tabelle `alk_messages`
--
ALTER TABLE `alk_messages`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
--
-- AUTO_INCREMENT für Tabelle `alk_monster`
--
ALTER TABLE `alk_monster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `alk_skills`
--
ALTER TABLE `alk_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `alk_user`
--
ALTER TABLE `alk_user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
