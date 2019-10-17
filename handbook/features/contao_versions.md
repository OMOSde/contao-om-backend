### Feature 'Contao-Version'

#### Motivation

Ich wünschte mir eine Information im Backend (nicht allzu aufdringlich), wo ich sehen kann, welche Contao-Version aktuell ist. (wie früher in den Systemnachrichten)

#### Konfiguration

Die Konfiguration erfolgt über eine einfache Checkbox in den Systemeinstellungen.

#### Umsetzung

Die Funktion führt einen täglichen Versionsabgleich (per TL_CRON) der Contao-Versionen mit Github aus und schreibt diese Information in die Datei /system/config/localconfig.php.

Die ermittelten Werte werden dann zusätzlich im Backend links unten bei der Versionsnummer angezeigt.

#### Hinweise

- bei LTS-Versionen wird immer die neueste Bugfix-Version für die installierte LTS-Version angezeigt

#### ToDo

- eventuell wäre das Einfärben der Information noch interessant