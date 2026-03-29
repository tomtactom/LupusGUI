# LupusGUI Dokumentation

> **Zielgruppe:** Entwickler:innen, Administrator:innen und Anwender:innen (Content-/Community-Management).  
> **Zweck:** Diese Dokumentation ist als praxisnahes Handbuch für Installation, Betrieb, Konfiguration, Nutzung und Weiterentwicklung von LupusGUI gedacht.

---

## Inhaltsverzeichnis

1. [Produktüberblick](#1-produktüberblick)
2. [Systemvoraussetzungen](#2-systemvoraussetzungen)
3. [Installation & Setup](#3-installation--setup)
4. [Projektstruktur](#4-projektstruktur)
5. [Routing & URL-Verhalten](#5-routing--url-verhalten)
6. [Konfigurationsmodell (Optionen)](#6-konfigurationsmodell-optionen)
7. [Rollen- und Berechtigungssystem](#7-rollen--und-berechtigungssystem)
8. [Kernfunktionen für Anwender:innen](#8-kernfunktionen-für-anwenderinnen)
9. [Backend-Funktionen für Admin/Support](#9-backend-funktionen-für-adminsupport)
10. [Datenbankmodell](#10-datenbankmodell)
11. [Plugin-System](#11-plugin-system)
12. [Betrieb, Wartung & Monitoring](#12-betrieb-wartung--monitoring)
13. [Sicherheit & Datenschutz](#13-sicherheit--datenschutz)
14. [Entwicklerleitfaden](#14-entwicklerleitfaden)
15. [Troubleshooting (FAQ für Betrieb)](#15-troubleshooting-faq-für-betrieb)
16. [Roadmap-Empfehlungen](#16-roadmap-empfehlungen)

---

## 1. Produktüberblick

LupusGUI ist ein PHP-basiertes CMS mit klarer Trennung zwischen:

- **Frontend** (`/website`): öffentliche Seiten, Registrierung, Login, Profil, FAQ, Kontakt.
- **Backend** (`/backend`): Dashboard, Benutzerverwaltung, FAQ-Moderation, Systemeinstellungen, Update.

Der Kern basiert auf einer klassischen PHP-Architektur mit:

- Seiten-Entrypoints (`website/*.php`, `backend/*.php`)
- zentralen Hilfsfunktionen (`include/functions.inc.php`)
- globalem Konfig-/Optionen-Loader (`include/settings.inc.php`)
- modularer Logik in Plugins (`include/plugins/*.inc.php`)
- HTML-Templates (`include/plugins/htmltemplates/*.php`).

---

## 2. Systemvoraussetzungen

Laut Setup-Konfiguration werden mindestens benötigt:

- PHP **>= 5.6**
- PHP-Extensions: `mysqli`, `pdo`, `pcre`
- Schreibrechte auf:
  - `include/database/database.php`
  - `include/tmp`

Empfohlen für produktiven Betrieb:

- HTTPS
- aktuelles PHP mit Security-Patches
- SMTP-fähige Mailkonfiguration
- regelmäßige Datenbank-Backups

---

## 3. Installation & Setup

## 3.1 Setup starten

Setup über `/setup` aufrufen. `setup/index.php` initialisiert den Prozess und erzeugt bei Bedarf `include/database/database.php`.

## 3.2 Setup-Fluss

Der Wizard wird über `nextStep` gesteuert und führt typischerweise durch:

1. Einführung
2. Anforderungen prüfen
3. Dateirechte prüfen
4. Datenbank konfigurieren
5. SQL-Import ausführen
6. Grundsettings speichern
7. Setup abschließen

## 3.3 Datenbank

Die Initialstruktur wird aus `setup/config/import.sql` importiert.

Wichtige Tabellen:

- `users`
- `securitytokens`
- `Statistiken`
- `faq`
- `option`

## 3.4 Nach dem Setup

- Prüfen, ob Frontend erreichbar ist (`/`)
- Erstes Adminkonto anlegen/prüfen
- `siteurl` und Mailkonfiguration kontrollieren
- Registrierung testweise durchlaufen

---

## 4. Projektstruktur

```text
.
├── website/                         # Frontend-Routen
├── backend/                         # Backend-Routen
├── include/
│   ├── functions.inc.php            # Core-Hilfsfunktionen
│   ├── settings.inc.php             # Optionen + Rechte + Pfade
│   ├── plugins/                     # Feature-Logik
│   │   ├── *.inc.php
│   │   ├── faq/
│   │   ├── statistic/
│   │   └── htmltemplates/
│   ├── website/                     # Frontend-Layoutteile
│   ├── backend/                     # Backend-Layoutteile
│   └── database/                    # DB-Datei, Profilbilder, Fonts
├── setup/                           # Installer
└── .htaccess                        # Rewrites + Zugriffsschutz
```

---

## 5. Routing & URL-Verhalten

`.htaccess` setzt folgendes Verhalten:

- Rewrite von Pretty-URLs auf `.php`
- direkter Zugriff auf `*.inc.php` ist gesperrt
- spezielle Profil-Rewrites:
  - `/profile@username`
  - `/backend/profile@username`
- Fallback unbekannter Pfade auf `/website/*`

**Auswirkung für Entwickler:innen:** Neue Seiten werden primär als `*.php`-Entrypoint umgesetzt; Rewrites ergänzen bei Bedarf eine schönere URL.

---

## 6. Konfigurationsmodell (Optionen)

LupusGUI nutzt eine zentrale `option`-Tabelle als Key-Value-Store.

Typische Optionsfelder:

- Site: `siteurl`, `sitename`, `sitedescription`, `keywordsmain`
- SEO: `robots`
- Auth/Betrieb: `allowregister`, `mainrole`, `mindestalter`
- Darstellung: `backenddesign`, `frontenddesign`, `font`, `fontname`, Farben
- Sprache/Land: `language`, `country`
- Kontakt/Sicherheit: `adminemail`, `recaptcha_sitekey`, `recaptcha_secretkey`
- Release: `version`

`include/settings.inc.php` liest alle Optionen und erzeugt Laufzeitwerte wie:

- `backendstylesheeturl`
- `frontendstylesheeturl`
- `short_language` (`de`/`en`)

---

## 7. Rollen- und Berechtigungssystem

## 7.1 Rollen

Im Code verwendete Rollen:

- `user`
- `member`
- `supporter`
- `manager`
- `adminstrator` *(überall so geschrieben)*

## 7.2 Zugriffsmuster

Der Zugriff wird über `$show_only_user` pro Seite gesteuert und in `include/settings.inc.php` geprüft.

Verwendete Mindeststufen:

- `min_member`
- `min_supporter`
- `min_manager`
- `adminstrator`

## 7.3 Beispielmatrix

- **Frontend Profil:** mindestens eingeloggter User
- **Backend Dashboard:** Supporter+
- **FAQ-Moderation:** Supporter+
- **Settings/User/Update:** Manager+

---

## 8. Kernfunktionen für Anwender:innen

## 8.1 Registrierung

- Route: `/register`
- Validierung von Name, E-Mail, Passwort, Alter, Username
- Rolle standardmäßig über `mainrole`
- Aktivierungslink per E-Mail

## 8.2 Account-Aktivierung

- Route: `/authentication`
- Aktivierung über `userid + code`

## 8.3 Login/Logout

- Login über E-Mail **oder** Username
- Optional „angemeldet bleiben“ (Token-Cookies)
- Logout löscht Session und Persistenzcookies

## 8.4 Passwort zurücksetzen

- `/forgotpassword`: Resetlink an E-Mail
- `/resetpassword`: neues Passwort setzen

## 8.5 Profilfunktionen

- eigenes Profil anzeigen/bearbeiten
- öffentliches Profil per `/profile@username`
- Biografie, Username, E-Mail, Passwort ändern
- Profilbild hochladen/löschen (JPG, Thumbnail-Erstellung)
- Account löschen

## 8.6 FAQ-Nutzung

- Fragen stellen (optional E-Mail)
- Suche nach Fragen/Antworten
- öffentliche Anzeige beantworteter Fragen

## 8.7 Kontaktformular

- Kategorie, Nachricht, E-Mail
- reCAPTCHA-Prüfung
- Bestätigungs- und Admin-Mail

---

## 9. Backend-Funktionen für Admin/Support

## 9.1 Dashboard (`/backend`)

- Rollenbezogene Begrüßung
- Nutzerübersicht (rollenabhängig)
- Statistik-Charts

## 9.2 FAQ-Moderation (`/backend/faq`)

- Fragen bearbeiten/löschen
- Antworten veröffentlichen
- optional Antwort-Mail an Fragesteller

## 9.3 Benutzerverwaltung (`/backend/user`)

- User-Liste
- Profildaten, Rolle, Passwort administrieren
- Profilbild für Nutzer verwalten
- Benutzer löschen

## 9.4 Systemeinstellungen (`/backend/settings`)

- zentrale Konfigurationsfelder bearbeiten
- Frontend-/Backend-Design wählen
- Designpakete (ZIP) hochladen/löschen
- Fonts hochladen/löschen

## 9.5 Update (`/backend/update`)

- sucht verfügbare Versionen über Update-Server
- lädt ZIP-Update
- entpackt und überschreibt Dateien
- aktualisiert `option.version`

---

## 10. Datenbankmodell

## 10.1 `users`

Speichert Auth- und Profildaten inkl. Rollen, Codes und Zeitfelder.

## 10.2 `securitytokens`

Speichert Persistenz-Login-Token (`identifier`, `securitytoken`) je Nutzer.

## 10.3 `Statistiken`

Speichert anonyme Nutzungs-/Systemdaten für Auswertungen.

## 10.4 `faq`

Speichert eingehende Fragen und redaktionelle Antworten.

## 10.5 `option`

Globaler Konfigurationsspeicher (Key-Value).

---

## 11. Plugin-System

Plugins liegen in `include/plugins/` und werden im Head-Flow geladen.

Aktuell wird zentral via `loadplugins.inc.php` das Statistik-Plugin initialisiert.

Typische Plugin-Aufteilung:

- `register.inc.php`, `login.inc.php`, `logout.inc.php`
- `forgotpassword.inc.php`, `resetpassword.inc.php`, `authentication.inc.php`
- `profile.inc.php`, `settings.inc.php`, `user.inc.php`
- `websitesettings.inc.php`, `update.inc.php`
- `contact.inc.php`
- `faq/faq.inc.php`, `faq/faqcontrol.inc.php`
- `statistic/*`

---

## 12. Betrieb, Wartung & Monitoring

## 12.1 Regelbetrieb (Checkliste)

Täglich:

- Login/Registrierung kurz prüfen
- Fehlerhinweise im UI kontrollieren
- Kontakt-/FAQ-Eingänge prüfen

Wöchentlich:

- Backup-Test (Restore prüfen)
- Rolle/Rechte-Stichprobe
- Statistik auf Ausreißer prüfen

Vor jedem Update:

1. Vollbackup (Code + DB)
2. Wartungsfenster planen
3. Update ausführen
4. Smoke-Test: Login, Profil, FAQ, Kontakt, Backend

## 12.2 Empfohlene Backups

- Datenbank-Dump täglich
- Dateien mindestens täglich (oder snapshot-basiert)
- Aufbewahrung mit Rotation (z. B. 7/30/90 Tage)

---

## 13. Sicherheit & Datenschutz

## 13.1 Technische Sicherheitsaspekte

- Passwörter werden gehasht gespeichert (`password_hash`)
- Login-Prüfung via `password_verify`
- Persistente Sessions über Securitytokens
- Direktzugriff auf `*.inc.php` per Serverregel blockiert

## 13.2 Betriebsrelevante Risiken

- Update-Prozess überschreibt Dateien direkt
- externe Dienste notwendig:
  - Mailversand
  - reCAPTCHA
  - Geo-IP
  - Update-Server

## 13.3 Datenschutz

- Kontakt- und FAQ-Funktionen speichern personenbezogene Daten (E-Mail)
- Statistik erfasst Nutzungsdaten bei Cookie-Einwilligung
- Betreiber muss rechtliche Texte und Prozesse (DSGVO) aktuell halten

---

## 14. Entwicklerleitfaden

## 14.1 Neue Seite anlegen (Standard)

1. Neue Route als `website/foo.php` oder `backend/foo.php`
2. Vor dem Head Include setzen:
   - `$show_only_user`
   - `$title`, `$description`, `$keywords`
3. `include/.../head.inc.php` einbinden
4. Plugin-Logik laden (`require($options['pluginpath'].'...')`)
5. Template laden (`require($options['pluginhtmlpath'].'...')`)
6. Footer einbinden

## 14.2 Neues Plugin anlegen

Empfohlen:

- Logik in `include/plugins/<name>.inc.php`
- HTML in `include/plugins/htmltemplates/<name>.html.inc.php`
- Route-Datei bindet beide ein

## 14.3 Datenbankänderungen

- Neue Felder sauber in Migrations-Skript dokumentieren
- `option`-basierte Features mit Defaultwert versehen
- Validierung in Plugin-Logik + Formular anpassen

## 14.4 Code-Konventionen (praktisch)

- Eingaben immer validieren
- SQL möglichst vorbereitet (Prepared Statements)
- `$_SERVER['DOCUMENT_ROOT']` konsistent verwenden
- bei Dateiuploads Typ und Größe serverseitig prüfen

---

## 15. Troubleshooting (FAQ für Betrieb)

## Problem: Setup startet immer wieder

- Prüfen, ob DB-Verbindung in `include/database/database.php` korrekt ist
- Prüfen, ob `option`-Tabelle vorhanden und lesbar ist

## Problem: Login funktioniert, aber keine Persistenz

- Cookies `identifier`/`securitytoken` prüfen
- Tabelle `securitytokens` auf Einträge prüfen

## Problem: Keine E-Mails kommen an

- PHP-Mailkonfiguration/SMTP prüfen
- `adminemail` und `siteurl` korrekt gesetzt?
- Spamfilter/DMARC/SPF prüfen

## Problem: Statistik leer

- Cookie-Zustimmung erforderlich (`allowCookies=true`)
- Browser-Cookies prüfen

## Problem: 404 oder falsche Route

- `.htaccess` aktiv?
- Rewrite-Modul aktiv?
- DocumentRoot korrekt?

---

## 16. Roadmap-Empfehlungen

Für langfristig stabilen Betrieb empfehlenswert:

1. einheitlich PDO statt Mischbetrieb mit mysqli
2. dedizierte Migrationen statt reinem Setup-SQL
3. sauberer Mailer (SMTP-Library) statt `mail()`
4. Logging/Observability (Errorlog + Auditlog)
5. Sicherheits-Härtung beim Update-Mechanismus
6. automatische Tests (Smoke + Integration)

---

## Abschluss

Diese Dokumentation ist absichtlich **developerorientiert und anwenderorientiert** aufgebaut:

- Entwickler:innen erhalten Struktur, Flows und Erweiterungspfade.
- Admins/Support erhalten Betrieb, Checklisten und Troubleshooting.
- Anwender:innen finden die nutzbaren CMS-Funktionen nachvollziehbar beschrieben.

