# Bestellung erneut Senden

JTL-Shop 5 Plugin von Oliver Kamps — ermöglicht das Zurücksetzen des Abgeholt-Status direkt aus dem Shop-Backend, damit Bestellungen erneut an die Warenwirtschaft übertragen werden können.

---

## Funktionsweise

Bleibt eine Bestellung beim Abholen durch die Wawi hängen, wird ihr `cAbgeholt`-Status auf `P` (Pending) gesetzt. Das Plugin erkennt diese Bestellungen und stellt Werkzeuge bereit, um den Status zurückzusetzen, sodass die Wawi die Bestellung beim nächsten Abholen erneut verarbeitet.

---

## Features

### Dashboard-Widget
Das Widget im Admin-Dashboard zeigt auf einen Blick, wie viele Bestellungen aktuell im Status Pending stecken. Bei mindestens einer Bestellung wird die Zahl rot hervorgehoben. Ein Direktlink führt zur Übersichtsseite des Plugins.

### Benachrichtigung
Sobald mindestens eine Bestellung den Status Pending hat, erscheint eine Meldung im JTL-Backend-Benachrichtigungsbereich mit einem Link zur Plugin-Übersicht.

### Übersicht & Bulk-Reset
Die Übersichtsseite listet alle Bestellungen mit Status Pending in einer Tabelle. Über Checkboxen können einzelne oder alle Einträge ausgewählt und mit einem Klick auf **Auswahl zurücksetzen** gemeinsam auf den Status `N` zurückgesetzt werden.

| Spalte | Inhalt |
|---|---|
| Bestellnummer | Interne Bestellnummer |
| Zahlungsart | Name der Zahlungsmethode |
| Gesamtsumme | Brutto-Bestellwert in Euro |
| Bestelldatum | Datum und Uhrzeit der Bestellung |

---

## Kompatibilität

| Eigenschaft | Wert |
|---|---|
| Mindest-Shopversion | 5.3.4 |
| Maximale Shopversion | 5.8.4 |

---

## Changelog

### 1.2.4
- Kompatibilität mit JTL-Shop 5.8.4 geprüft: die Core-Änderungen 5.8.3 → 5.8.4 (Sicherheitsupdate) betreffen nur Lieferadressen-Prüfung im Checkout, OPC-Vorschau-Token, IP-Ermittlung hinter Proxys, CSRF-Schutz im Checkout, Rate-Limits für Wawi-Login und Backend, Backend-Benutzer- und Einstellungsverwaltung sowie Bibliotheks-Updates (Guzzle 7.15, Smarty 5.8); keine vom Plugin genutzte API und keine überschriebenen NOVA-Templates sind betroffen. MaxShopVersion auf 5.8.4 angehoben

### 1.2.3
- Kompatibilität mit JTL-Shop 5.8.3 geprüft: die Core-Änderungen 5.8.2 → 5.8.3 (Sicherheits- und Performance-Update) betreffen nur Backend-Benutzerverwaltung/2FA/Rechteprüfung, Backend-Benachrichtigungen, das Template-Änderungsprotokoll, den Wawi-Bildabgleich, die Zahlungsart-Prüfung im Checkout sowie die SQL von Preissortierung und Filtern (MariaDB 10.11); NOVA-Templates und Plugin-API sind unverändert. MaxShopVersion auf 5.8.3 angehoben

### 1.2.2
- Kompatibilität mit JTL-Shop 5.8.2 geprüft: die Core-Änderungen 5.8.0 → 5.8.2 (Sicherheitsupdate) betreffen nur Consent-Manager, RMA, Sonderpreis-Berechnung, Banner/ImageMap, PLZ-Autovervollständigung und Backend-Login; NOVA-Templates und Plugin-API sind unverändert. MaxShopVersion auf 5.8.2 angehoben

### 1.2.1
- Kompatibilität mit JTL-Shop 5.8.0 geprüft (Bootstrapper, AbstractWidget, GenericModelController, Notification, Smarty 5.7 unverändert), MaxShopVersion auf 5.8.0 angehoben

### 1.2.0
- Übersichtsseite mit Checkbox-basiertem Bulk-Reset überarbeitet
- Tab „Status zurücksetzen" entfernt (Reset jetzt direkt in der Übersicht)
- Beschreibungstext und Widget-Template bereinigt
- MaxShopVersion auf 5.7.0 angehoben

### 1.1.0
- Neue Übersichtsseite mit Tabelle aller Pending-Bestellungen
- Checkboxen zur Mehrfachauswahl und Reset-Button
- Design an JTL-Admin-Standard angeglichen

### 1.0.4
- Benachrichtigung im Backend, sobald eine Bestellung den Status Pending hat

### 1.0.1
- Admin-Widget zeigt Anzahl der Pending-Bestellungen an

### 1.0.0
- Initiale Version
