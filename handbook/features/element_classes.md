### Feature 'Element-Klassen'

#### Motivation

Die 'Element-Klassen' wurden für Redakteure konzipiert. Die Erfahrung zeigt, das den Redakteuren oft das Verständnis für CSS und somit auch für CSS-Klassen fehlt. Daraus ergibt sich (zumindest für mich), das eine einfachere Form der Verwendung von CSS-Klassen für Redakteure gefunden werden muss.

Die Idee ist, das für Artikel und Inhaltselemente CSS-Klassen mit erklärendem Text erstellt werden können. Die Redakteure klicken dann quasi nur noch eine Checkbox an. Zum Beispiel 'Hintergrund in Blau'. Intern wird aber dem jeweilige Element einfach nur eine neue CSS-Klasse hinzugefügt und diese übernimmt dann die veränderte Darstellung. 

#### Umsetzung

Die CSS-Klassen können innerhalb das neuen Backend-Modules 'Element-Klassen' erstellt werden. Ich denke der Aufbau ist relativ selbsterklärend. Falls notwendig können mehrere Sprachen verwendet werden.

#### Hinweise

- Wenn für eine Artikel oder Element keine zusätzlichen CSS-Klassen definiert sind, wird das entsprechende Feld im Backend nicht angezeigt.
- Es können mehrere Einträge für Artikel oder ein spezielles Inhaltselement erstellt werden. Das macht gegebenenfalls bei größeren Projekten Sinn.
- Es erfolgt keine Prüfung, ob eine Sprache mehrfach vorhanden ist. In diesem Fall wird stets der letzte gefundende Text verwendet werden.
- Falls keine zugehörige Sprache gefunden wird, dann wird immer der erste gefundene Text verwendet.

#### ToDo

Ich bin mir noch nicht sicher, ob das Feature gegebenenfalls für Formulare oder Module sinnvoll ist. Ich benötigte bisher nur die Artikel und Inhaltselemente.