Bulletin Board für Contao
=========================

Ein Forum besteht aus Themen in denen die Nutzer Beiträge schreiben können. Die
Themen werden können in mehrere (Sub)Foren untergliedert werden. Diese Foren
können wiederum in Kategorien eingeteilt werden.

Laut Wikipedia kann man zwischen einem "klassischem" Forum und einem Board
unterscheiden. Bei einem "klassischen" Forum wird jeder Beitrag als Antwort
unter einen anderen Beitrag einsortiert. Es steht so eine Baumstruktur für jedes
Thema. In einem Board werden die Beitrage flach zeitlich aufsteigend pro Thema
angezeigt. Um den Bezug zu anderen Beiträgen hervorzuheben hat sich in Boards
die Schreibweise "@Beitrag XYZ" oder "@Nutzer ABC" eingebürgert.


Datenmodell
-----------

**Kategorie**
- Name

**Forum**
- Name
- Beschreibung
- gehört zu Kategorie XYZ

**Thema**
- Name
- Verfasser
- Zeitpunkt der Erstellung
- gehört zu Forum XYZ

**Beitrag**
- Betreff
- Verfasser
- Zeitpunkt der Erstellung
- Text
- gehört zu Thema XYZ
- ist Antwort auf Beitrag XYZ

*Hinzukommen noch Metainformationen:*
- Nummer der Kategorie für Sortierung
- Nummer des Fourms für Sortierung
- Anzahl der Themen eines Forums
- Anzahl der Beitrag eines Forums
- Letzter Beitrag eines Forums
- Nummer des Themas für Sortierung
- Anzahl der Antworten auf ein Thema
- Anzahl der Zugriffe auf ein Thema
- Letzter Beitrag eines Themas
- Nummer des Beitrags für Sortierung
- Letzter Bearbeiter eines Beitrags
- Zeitpunkt des letzten Bearbeitens eines Beitrags

*Die Mitglieder im Contao müssen erweitert werden:*
- Anzahl der Beiträge
- Signatur
- Avatar
- Flag ob der echte Name angezeigt werden soll oder nur der Anmeldename
  (Pseudonym)


Weitere Funktionen
------------------

- E-Mail-Benachrichtigung bei neuen Beiträgen zu einem abonnierten Thema
- Suche nach Themen und Beiträgen
- Rechte zum Lesen von Foren
- Rechte zum Lesen und Beantworten von Themen
- BBCode für die Texteingabe
- Smilies für die Texteingabe
- Upload von Dateien
- Moderation von Themen (Verschieben, Schließen, Löschen)
- Moderation von Beiträgen (Bearbeiten, Löschen)
- RSS-Feed
- Beiträge per E-Mail beantworten

---

Es sind noch nicht alle oben genannten Funktionen implementiert. Diese
Zusammenstellung ist mehr so etwas wie eine Vision oder ein Plan.
