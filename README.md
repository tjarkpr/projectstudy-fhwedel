# Projektstudie
Projektstudie FH-Wedel WS2019 - (Browserbasiertes) Grafisches Tool zum Planen und Verfolgen des Studienablaufs

## Allgemein
Web-Applikation zur Organisation und Speicherung eines Studienverlaufsplans. Basierend auf PHP, JS, CSS, SQL und HTML wird ein User Interface bereitgestellt, um den eigenen Studienverlaufsplan zu verändern und bestandene Leistungen einzutragen.

## Funktionalitäten und grundsätzlicher Aufbau der Website
Die Website soll einem Benutzer dabei helfen, seinen geplanten Verlauf des Studiums durch grafische Elemente zu visualisieren, und dabei eine möglichst einfache Handhabung bieten. Hierzu wird der vom Benutzer hinterlegte Studienverlaufsplan nicht lokal auf seinem Interface, sondern auf einem Webserver gespeichert. So kann ein Zugriff seiner Daten von überall aus erfolgen, und er ist nicht auf ein einzelnes PC- System, oder eine Datei, beschränkt.
Die Website bietet dem Benutzer die Möglichkeit, Fächer anhand ihrer Semesteranordnung zu strukturieren, in andere Semester zu verschieben und auf allgemeine Informationen zu dem Fach, wie Restriktionen nach Semestern, zuzugreifen. Hierzu dient eine schlichte Tabelle, welche die einzelnen Semester mit den beinhalteten Fächern darstellt. Der Benutzer kann in dieser Tabelle besagte Operationen durchführen, bzw. durch eine weitere Tabelle, zusätzliche Fächer in seine Semesterplanung einfügen.
Bestandene Fächer/Klausuren können von dem Benutzer als solche markiert werden, sodass sie in der Übersicht als solche schnell erkannt werden können.
Zur Vereinfachung der Handhabung der Website, muss der Benutzer keinerlei Informationen zu den einzelnen Fächern selbst angeben. Die Informationen zu den einzelnen Fächern, liegen in einer Datenbank auf dem Webserver für alle angemeldeten Benutzer bereit. Ein Admin übernimmt das Einpflegen neuer Fächer/Studiengänge und die Überarbeitung veralteter Versionen.
Um einen Missbrauch der Datenbank vorzubeugen, ist es nicht möglich sich auf der Website zu „Registrieren“. Eine Anmeldung des Benutzers erfolgt mit ihm bereitgestellten Anmeldedaten. Diese können von der Uni selbst übernommen werden, um eine Verwechslung vorzubeugen.

## Installation
Kann auf beliebigen Web-Servern installiert werden. Wichtig ist eine Datenbank und die Anpassung in der Konfiguration, um die Verbindung zur Datenbank herzustellen.

```php
/*Konfiguration des Datenbankzugangs*/
$ip = "localhost";
$port = "5050";
$username = "root";
$password = "admin";
```

Sollte es zu Komplikationen kommen, empfehlen wir die Verwendung von [XAMPP](https://www.apachefriends.org/de/index.html).

## Installation über XAMPP
1. XAMPP herunterladen.
2. Installation durchführen.
  a. Bei der Komponentenauswahl nur „Apache“, „MySQL“, „PHP“ und „phpMyAdmin“ auswählen.
3. In den Ordner von XAMPP navigieren.
4. Ordner „htdocs“ durch beigelegten „htdocs“ Ordner des Gruppenprojektes ersetzen.
5. „xampp-control.exe“ starten.
6. Module „Apache“ und „MySQL“ starten.
7. Bei Modul „MySQL“ auf „Admin“ klicken.
8. Auf der geöffneten phpMyAdmin Seite eine neue Datenbank mit dem Namen „Studienverlaufsplaner“ erstellen.
  a. Auf „Neu“ klicken.
  b. Name „Studienverlaufsplaner“ eingeben und „Anlegen“ klicken.
9. Erstellte Datenbank auswählen und Tabellen importieren.
  a. Dort die beigelegte Datei „Studienverlaufsplaner.sql“ auswählen und mit Klick auf „OK“ bestätigen.
10. Im Browser „localhost“ eingeben und Seite öffnen.

## Authoren
by Tjark, Niclas, Fynn & Kjell
