# LupusGUI

**LupusGUI** ist ein leichtgewichtiges, PHP-basiertes CMS mit integrierter Community- und Account-Verwaltung.
Es richtet sich an Teams, die schnell eine moderne Website mit Mitgliederbereich, Backend und klaren Abläufen betreiben wollen – ohne ein überladenes System.

## Warum LupusGUI?

- **Schnell startklar:** Setup-Wizard für Installation, Datenbank und Grundkonfiguration.
- **Frontend + Backend aus einem Guss:** Öffentliche Seiten und Admin-Bereich sind sauber getrennt.
- **Starker Fokus auf Nutzer-Workflows:** Registrierung, Aktivierung, Login, Passwort-Reset und Profilpflege sind direkt enthalten.
- **Praxisnahe Community-Funktionen:** FAQ-System mit Moderation und Kontaktformular mit reCAPTCHA.
- **Anpassbar statt starr:** Plugin-Struktur, Design-Slots für Frontend/Backend und zentrale Optionen für Betrieb, Sprache und Darstellung.

## Das kann LupusGUI

### Für Besucher:innen und Mitglieder
- Registrierung mit E-Mail-Aktivierung
- Login (E-Mail oder Username) inkl. „angemeldet bleiben“
- Passwort vergessen / Passwort zurücksetzen
- Öffentliches Profil per Pretty-URL (`/profile@username`)
- Profilverwaltung inkl. Profilbild-Upload
- FAQ lesen und Fragen einreichen
- Kontaktformular mit Schutzmechanismen

### Für Admins und Support-Teams
- Rollenbasiertes Backend (z. B. Support, Management, Administration)
- FAQ-Moderation (bearbeiten, beantworten, veröffentlichen)
- Benutzerverwaltung und Account-Pflege
- Zentrale Einstellungen für Website, SEO, Design und Sprache
- Statistik-/Auswertungsfunktionen im Backend

## Was LupusGUI von vielen CMS-Systemen abhebt

1. **Community zuerst statt nur Seitenverwaltung**  
   LupusGUI bringt Nutzerkonten, Profile, FAQ und Support-nahe Prozesse standardmäßig mit.

2. **Klarer, nachvollziehbarer Aufbau**  
   Klassische PHP-Struktur mit gut trennbaren Bereichen (`website/`, `backend/`, `include/plugins/`) erleichtert Wartung und Weiterentwicklung.

3. **Direkt einsetzbar für Mitglieder- und Serviceportale**  
   Statt viele Drittmodule zusammenzustellen, sind zentrale Funktionen für Interaktion und Betreuung bereits integriert.

## Technischer Überblick

- **Technologie:** PHP + MySQL/MariaDB
- **Architektur:** Route-Dateien + zentrale Includes + Plugin-Module
- **Installation:** Browser-basiertes Setup unter `/setup`
- **Betrieb:** Geeignet für klassische Shared-Hosting- und Server-Umgebungen

## Schnellstart

1. Projekt auf den Webserver legen
2. Datenbank bereitstellen
3. `https://deine-domain.tld/setup` aufrufen
4. Setup-Schritte durchlaufen
5. Danach Frontend und Backend testen

> Ausführliche Informationen zu Struktur, Betrieb und Entwicklung findest du in der `DOCUMENTATION.md`.

---

## Für wen ist LupusGUI gemacht?

LupusGUI ist ideal, wenn du …
- eine **Website mit Mitgliederbereich** suchst,
- ein **leicht verständliches CMS** bevorzugst,
- ein System willst, das **Support- und Community-Abläufe** direkt unterstützt,
- und lieber **produktiv startest**, statt erst viele Plugins kombinieren zu müssen.

Wenn du ein pragmatisches CMS mit Fokus auf Menschen, Accounts und Prozesse suchst, ist **LupusGUI** eine starke Wahl.
