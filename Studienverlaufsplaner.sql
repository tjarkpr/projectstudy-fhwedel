-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 13. Jan 2020 um 10:25
-- Server-Version: 10.4.8-MariaDB
-- PHP-Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `Studienverlaufsplaner`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Administrator`
--

CREATE TABLE `Administrator` (
  `A-ID` int(11) NOT NULL,
  `Benutzername` varchar(50) NOT NULL,
  `Passwort` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `Administrator`
--

INSERT INTO `Administrator` (`A-ID`, `Benutzername`, `Passwort`) VALUES
(1, 'admin', '$2y$10$bkOClflr2bo4hvOhpEVGM.VIWk6tYcvFe8W9S5NxhPd2ifulmHdRW');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Benutzer`
--

CREATE TABLE `Benutzer` (
  `B-ID` int(11) NOT NULL,
  `Benutzername` varchar(50) NOT NULL,
  `Passwort` varchar(400) NOT NULL DEFAULT '$2y$10$7n7E/rYRm7Bvg532sWK2ZeaKM9iaw/9W5oG2LeOf2PkZRgtBpW5CG',
  `Curriculum` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '{"Semester":[{"Prüfungen":[]}]}',
  `S-ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `Benutzer`
--

INSERT INTO `Benutzer` (`B-ID`, `Benutzername`, `Passwort`, `Curriculum`, `S-ID`) VALUES
(1, 'winf0001', '$2y$10$7n7E/rYRm7Bvg532sWK2ZeaKM9iaw/9W5oG2LeOf2PkZRgtBpW5CG', '{\"Semester\":[{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B001\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B002\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B034\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B017\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B005\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B003\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B019\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B021\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B042\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B020\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B044\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B022\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B041\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B040\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B037\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B052\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B093\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B036\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B086\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B082\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B057\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B058\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B059\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B080\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B087\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B098\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B115\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B112\",\"Bestanden\":false},{\"Typ\":\"S_WAHL\",\"W-ID\":\"W0\",\"Länge\":2,\"P-IDs\":[{\"Typ\":\"W_FACH\",\"P-ID\":\"B095\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B096\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B054\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B055\",\"Bestanden\":false,\"RW-ID\":\"W0\"}]}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B122\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B121\",\"Bestanden\":false,\"Länge\":2},{\"Typ\":\"S_FACH\",\"P-ID\":\"B118\",\"Bestanden\":false},{\"Typ\":\"S_WAHL\",\"W-ID\":\"W1\",\"Länge\":2,\"P-IDs\":[{\"Typ\":\"W_FACH\",\"P-ID\":\"B117\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B094\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B124\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B100\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B123\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B061\",\"Bestanden\":false,\"RW-ID\":\"W1\"}]}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B150\",\"Bestanden\":false,\"Länge\":6}]}]}', 1),
(2, 'winf0002', '$2y$10$7n7E/rYRm7Bvg532sWK2ZeaKM9iaw/9W5oG2LeOf2PkZRgtBpW5CG', '{\"Semester\":[{\"Prüfungen\":[]}]}', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Prüfung`
--

CREATE TABLE `Prüfung` (
  `P-ID` varchar(200) NOT NULL,
  `Name` varchar(400) NOT NULL,
  `Angebot` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `Prüfung`
--

INSERT INTO `Prüfung` (`P-ID`, `Name`, `Angebot`) VALUES
('B001', 'Mathematik 1', 'E'),
('B002', 'Diskrete Mathematik', 'E'),
('B003', 'Programmstrukturen 1', 'E'),
('B005', 'Rechnungswesen 1', 'E'),
('B006', 'Einführung in Digitaltechnik', 'E'),
('B008', 'Chemie, Chemietechnik', 'W'),
('B012', 'Physik 1', 'W'),
('B016', 'Einführung in die Programmierung', 'W'),
('B017', 'Volkswirtschaft', 'E'),
('B019', 'Mathematik 2', 'S'),
('B020', 'Programmstrukturen 2', 'E'),
('B021', 'Finanzwirtschaft', 'S'),
('B022', 'Theoretische Informatik', 'S'),
('B023', 'Rechnerstruktur und Digitaltechnik', 'S'),
('B024', 'Rechnungswesen 2', 'S'),
('B025', 'Materialtechnik', 'S'),
('B026', 'Physik 2', 'S'),
('B029', 'Technische Kommunikation', 'S'),
('B033', 'Business and Commercial English', 'W'),
('B034', 'Betriebswirtschaftslehre', 'E'),
('B035', 'Office Anwendungen', 'S'),
('B036', 'Programmierpraktikum', 'E'),
('B037', 'Rechnernetze', 'W'),
('B040', 'Algorithmen und Datenstrukturen', 'W'),
('B041', 'Statistik', 'W'),
('B042', 'Datenschutz und Wirtschaftsprivatrecht', 'S'),
('B043', 'Systemnahe Programmierung', 'W'),
('B044', 'UNIX und Shell Programmierung', 'S'),
('B045', 'Lineare Algebra', 'W'),
('B046', 'Ingenieurmathematik', 'W'),
('B052', 'Datenbanken 1', 'W'),
('B054', 'DLM und Marketing & Medien', 'W'),
('B055', 'Produktionsmanagement 1', 'W'),
('B057', 'Fortgeschrittene objektorientierte Programmierung', 'S'),
('B058', 'Software Design', 'S'),
('B059', 'Web-Anwendungen', 'S'),
('B061', 'E-Commerce Grundlagen', 'W'),
('B080', 'Implementierung von Geschäftsprozesse in ERP-Systemen', 'S'),
('B081', 'Betriebswirtschaftliche Prozesse mit ERP-Systemen', 'S'),
('B082', 'Operation Research', 'E'),
('B086', 'Unternehmensführung', 'S'),
('B087', 'Systemmodellierung', 'W'),
('B093', 'Software Qualität', 'W'),
('B094', 'Produktionsmanagement 2', 'S'),
('B095', 'Anwendung der Künstlichen Intelligenz', 'W'),
('B096', 'Systemsoftware', 'W'),
('B098', 'Entwicklung in ERP-Systemen', 'W'),
('B099', 'Auslandssemester', 'E'),
('B100', 'Märkte, Strategien und Ressourcen', 'S'),
('B112', 'Projektstudie', 'E'),
('B115', 'Seminar', 'E'),
('B117', 'Datenbanken 2', 'S'),
('B118', 'Soft Skills', 'E'),
('B120', 'Entre- und Intrapreneurship', 'S'),
('B121', 'Software-Projekt', 'E'),
('B122', 'IT-Sicherheit', 'S'),
('B123', 'Prozessmodellimplementation', 'S'),
('B124', 'Logistikmanagement', 'S'),
('B150', 'Bachelor-Thesis', 'E'),
('B159', 'Betriebspraktkum', 'E'),
('B160', 'Bachelor-Kolloquim', 'E'),
('B161', 'Einführung IT-Management', 'W'),
('B162', 'Lebenszyklen von IT-Systemen', 'W'),
('B164', 'Projekt IT-Management, Consulting & Auditing', 'S'),
('B174', 'Seminar IT-Management, Consulting & Auditing', 'S'),
('B175', 'Beratungskompetenz', 'S'),
('B176', 'Praxissemester (dual)', 'E'),
('B179', 'Wissentschaftliche Aussarbeitung', 'E'),
('B209', 'Applied Data Science and Machine Learning', 'S'),
('B210', 'Strategisches IT-Management', 'S'),
('B211', 'IT Steuerung und IT-gestützes BPM', 'W'),
('B212', 'Internationale Rechnungslehre & Unternehmensbesteuerung 1', 'W'),
('B213', 'Konzernrechnungslegung & Unternehmensbesteuerung 2', 'S'),
('B214', 'Prüfungswesen & Praxisworkshops IT Audit', 'S'),
('B215', 'Finanzwirtschafts', 'S'),
('B216', 'Grundlagen der Betriebswirtschaftslehre', 'W');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Prüfung_vorbedingt_Prüfung`
--

CREATE TABLE `Prüfung_vorbedingt_Prüfung` (
  `P-ID` varchar(200) NOT NULL,
  `P-IDB` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `Prüfung_vorbedingt_Prüfung`
--

INSERT INTO `Prüfung_vorbedingt_Prüfung` (`P-ID`, `P-IDB`) VALUES
('B022', 'B002'),
('B040', 'B020'),
('B086', 'B005'),
('B057', 'B020'),
('B058', 'B020'),
('B059', 'B003'),
('B080', 'B034'),
('B098', 'B003'),
('B098', 'B020'),
('B098', 'B052'),
('B095', 'B002'),
('B095', 'B020'),
('B117', 'B052'),
('B123', 'B020'),
('B123', 'B052'),
('B123', 'B059'),
('B123', 'B087'),
('B100', 'B054'),
('B160', 'B150'),
('B212', 'B005'),
('B081', 'B216'),
('B046', 'B001'),
('B023', 'B006'),
('B020', 'B003'),
('B121', 'B036');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Studiengang`
--

CREATE TABLE `Studiengang` (
  `S-ID` int(11) NOT NULL,
  `Fachrichtung` varchar(400) NOT NULL,
  `Studienordnung` varchar(100) NOT NULL,
  `Startsemester` varchar(10) NOT NULL,
  `Obergrenze` int(11) NOT NULL DEFAULT 0,
  `Curriculum` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '{"Semester":[{"Prüfungen":[]}]}'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `Studiengang`
--

INSERT INTO `Studiengang` (`S-ID`, `Fachrichtung`, `Studienordnung`, `Startsemester`, `Obergrenze`, `Curriculum`) VALUES
(1, 'B.Sc. Wirtschaftsinformatik', '14.0', 'W', 5, '{\"Semester\":[{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B001\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B002\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B034\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B017\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B005\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B003\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B019\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B021\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B042\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B020\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B044\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B022\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B041\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B040\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B037\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B052\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B093\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B036\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B086\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B082\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B057\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B058\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B059\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B080\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B087\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B098\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B115\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B112\",\"Bestanden\":false},{\"Typ\":\"S_WAHL\",\"W-ID\":\"W0\",\"Länge\":2,\"P-IDs\":[{\"Typ\":\"W_FACH\",\"P-ID\":\"B095\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B096\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B054\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B055\",\"Bestanden\":false,\"RW-ID\":\"W0\"}]}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B122\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B121\",\"Bestanden\":false,\"Länge\":2},{\"Typ\":\"S_FACH\",\"P-ID\":\"B118\",\"Bestanden\":false},{\"Typ\":\"S_WAHL\",\"W-ID\":\"W1\",\"Länge\":2,\"P-IDs\":[{\"Typ\":\"W_FACH\",\"P-ID\":\"B117\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B094\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B124\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B100\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B123\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B061\",\"Bestanden\":false,\"RW-ID\":\"W1\"}]}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B150\",\"Bestanden\":false,\"Länge\":6}]}]}'),
(2, 'B.Sc. Wirtschaftsinformatik', '14.0', 'S', 5, '{\"Semester\":[{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B001\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B019\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B002\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B034\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B042\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B003\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B041\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B017\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B005\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B052\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B037\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B020\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B021\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B082\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B080\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B022\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B044\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B059\",\"Bestanden\":false}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B087\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B098\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B040\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B036\",\"Bestanden\":false},{\"Typ\":\"S_WAHL\",\"W-ID\":\"W0\",\"P-IDs\":[{\"Typ\":\"W_FACH\",\"P-ID\":\"B054\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B055\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B095\",\"Bestanden\":false,\"RW-ID\":\"W0\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B096\",\"Bestanden\":false,\"RW-ID\":\"W0\"}],\"Länge\":2}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B086\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B122\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B057\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B058\",\"Bestanden\":false},{\"Typ\":\"S_WAHL\",\"W-ID\":\"W1\",\"P-IDs\":[{\"Typ\":\"W_FACH\",\"P-ID\":\"B061\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B094\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B100\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B117\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B123\",\"Bestanden\":false,\"RW-ID\":\"W1\"},{\"Typ\":\"W_FACH\",\"P-ID\":\"B124\",\"Bestanden\":false,\"RW-ID\":\"W1\"}],\"Länge\":2}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B118\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B115\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B112\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B093\",\"Bestanden\":false},{\"Typ\":\"S_FACH\",\"P-ID\":\"B121\",\"Bestanden\":false,\"Länge\":2}]},{\"Prüfungen\":[{\"Typ\":\"S_FACH\",\"P-ID\":\"B150\",\"Bestanden\":false,\"Länge\":6}]}]}');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Administrator`
--
ALTER TABLE `Administrator`
  ADD PRIMARY KEY (`A-ID`),
  ADD UNIQUE KEY `UNIQUE_Benutzername` (`Benutzername`);

--
-- Indizes für die Tabelle `Benutzer`
--
ALTER TABLE `Benutzer`
  ADD PRIMARY KEY (`B-ID`),
  ADD UNIQUE KEY `UNIQUE_Benutzername` (`Benutzername`),
  ADD KEY `INDEX_S-ID` (`S-ID`);

--
-- Indizes für die Tabelle `Prüfung`
--
ALTER TABLE `Prüfung`
  ADD PRIMARY KEY (`P-ID`),
  ADD UNIQUE KEY `UNIQUE_Name` (`Name`);

--
-- Indizes für die Tabelle `Prüfung_vorbedingt_Prüfung`
--
ALTER TABLE `Prüfung_vorbedingt_Prüfung`
  ADD KEY `INDEX_P-ID` (`P-ID`),
  ADD KEY `INDEX_P-IDB` (`P-IDB`);

--
-- Indizes für die Tabelle `Studiengang`
--
ALTER TABLE `Studiengang`
  ADD PRIMARY KEY (`S-ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
