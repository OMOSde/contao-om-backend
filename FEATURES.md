
# OM-Backend - Featurelist

## 0. Vorwort

Dies ist eine überarbeitete und ergänzte Version des Moduls 'om_backend' für Contao 4.4.

## 1. Für Alle

Für das einfachere Arbeiten im Backend bringt das Modul einige neue Features mit. Einige davon müssen in den 
Benutzereinstellungen vorher aktiviert werden.

### 1.1 Eine Backend-Toolbar

Eine praktische Toolbar für wichtige Funktionen im Backend. Die Toolbar positioniert sich rechter Hand im Backend an 
einer fixen Stelle. Nachfolgend eine kleine Auflistung der Buttons und ihrer Funktion:

- ID-/Alias-Suche (wird weiter unten genauer beschrieben)
- Install-Tool (dieser Button verlinkte in der Version für Contao 3.5 auf die Datenbank-Aktualisierung, nun wird 
direkt ins Install-Tool verlinkt)
- ein neues Template erstellen (Kurzlink)
- Syncronisation der Dateiverwaltung (Kurzlink)

Für jedes gefunden Theme werden folgende 4 Buttons bereitgestellt:

- Stylesheets
- Module
- Seitenlayouts
- Bildgrößen

Außerdem werden noch die derzeit verfügbaren Speichern-Button hinzugefügt.

### 1.2 Sprache bei den Startpunkten hinzufügen

Wie der Titel besagt, werden bei allen Startpunkten die jeweilige Sprache hinzugefügt. Dies ist praktisch bei 
mehrsprachigen Projekten. Dies gilt auch für den neuen DCA-Picker.

### 1.3 ID-/Alias-Suche

Die ID-/Alias-Suche dient der direkten Auffindung von Datensätzen von denen die ID bzw. der Alias (falls in der 
Tabelle vorhanden) bekannt ist. Sollte der Datensatz gefunden werden, wird direkt zur Bearbeitung des Datensatzes 
gesprungen. Dies gilt auch für Tabelle, welche durch andere Backend-Module bereitgestellt werden.
 
Diese Suchfunktion ist auch Bestandteil der Toolbar.
 
### 1.4 ID´s mit 'Shift' anzeigen



### 1.5 Backend-Links

### 1.6 System-Informationen

Dieses Feature ist im Quellcode tatsächlich noch enthalten und derzeit nur mittels 'config.php' ausgeblendet. Der Markdown-Viewer hat bei mir dieses Feature ersetzt. Falls Bedarf daran besteht dieses Feature wieder zu aktivieren, bitte ich um Kontaktuafnahme. Ansonsten wird es wahrscheinlich später aus dem Quelltext entfernt.

### 1.7 Markdown-Viewer

Es werden alle Markdown-Dateien im Root-Verzeichnis der Contao-Installation (nicht in /web) im Backend zum Lesen aufbereitet. Dabei 
findet eine Umwandlung in das HTML-Format statt. Rudimentäres Standard-CSS für diese Ansicht liefert das bereits mit.

__Hinweis__: Im Grunde handelt es sich um eine ähnliche Funktion wie bei den 'System-Informationen'. Ich setze mittlerweile zur Darstellung von kundenspezifischen Informationen im Backend nur noch den Markdown-Viewer ein.

### 1.8 Suche nach Datei-Verwendungen


### 1.9 Kontaktlink

Dies kann unter System » Einstellungen konfiguriert werden und erstellt in der Backend-Top-Navigation einen zusätzlichen Link. Für diesen kann eine Titel, die URL und ein Icon angegeben werden.

__Einsatz__: Ich setze dies in meinen Projekten ein, um dem Kunden im Backend direkte Kontaktinformation zu mir, bereitzustellen.

## 2. Für Entwickler

Die nachfolgend beschriebenen Features richten sich in erster Linie an Entwickler. Sie sind standardmäßig aktiv und 
bedürfen keiner weiteren Konfiguration oder Rechtemanagement.

### 2.1 Backend Tabs

Mit diesem Feature lassen sich innerhalb eines Backend-Moduls weitere beliebige Backend-Module in Tabs darstellen. 
Das lässt sich am besten mittels Screenshots zeigen.

TODO: Screenshot einfügen

Die Konfiguration erfolgt dabei in der Datei config/config.php des Backend-Moduls. Nachfolgend der verwendete Code 
für den obigen Beispiel-Screenshot:

```
/**
 * Additional backend modules
 */
$GLOBALS['BE_MOD']['demo'] = array
(
    'demo' => array
    (
        'callback' => 'OMOSde\OmBackendBundle\ModuleBackendTabs',
        'tabs'     => array
        (
            'member',
            'mgroup',
            'id_search'
        )
    )
);
```

Hinweis: Die in Tabs verwendeten Module werden aus der Hauptnavigation des Backends entfernt.


### 2.2 Backend CSS

Dieses Feature ergänzt, für die Eigenschaft 'tl_class' in den Evaluation-Einstellungen des DCA, die Klassen 'w25', 
'w33', 'w66', 'w75' und 'heightAuto'. Die w-Klassen ermöglichen neue Kombinationsmöglichkeiten für die Anordnung der
 DCA-Felder im Backend. So sind nun beispielsweise 3 oder gar 4 Felder in einer Reihe möglich, was im neuen 
 Fullwidth-Layout des Backends durchaus Vorteile haben kann.
 
Die Klasse 'heightAuto' ist für Felder gedacht, die sich bisher nicht nebeneinander anordnen lassen. Dies betrifft 
zum Beispiel die Inputtypes 'textarea', 'fileTree', 'pageTree' und weitere. Diese lassen sich nun beispielsweise mit 
'tl_class'=>'w50 heightAuto' ebenfalls floaten.

Die neuen Klassen verhalten sich bei geringer Viewport-Breite genauso wie die bisherige Klasse 'w50'.

## 3. Nachwort

Das vorliegende Modul ist bei mir quasi in allen Contao-Projekte als Standard mit dabei. Es entstand aus Ideen und 
Anforderungen, die immer mal wieder aus den Projekten selbst hervorgingen.

Gerne nehme ich Ideen für Features entgegen.