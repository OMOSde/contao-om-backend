# Contao bundle change log

### 1.3.2 (2018-??-??)

 * Changed: Remove the feature fullwidth from 4.5, since it is no longer necessary

### 1.3.1 (2018-07-02)

 * Fixed: Theme buttons with wrong id´s (then multiple themes exists)
 * Fixed: Illegal string warning on rocksolid custom elements
 * Fixed: Search contao-manager.phar.php in wrong directory
 * Fixed: Dom manipulation has a problem with the markup from the mediamanager from isotope.
 * Fixed: Problem with script tag in allowed HTML tags / settings
 * Fixed: HTML-Entitäten werden in den Übersetzungen wieder dekodiert
 * Fixed: Add missing language labels

### 1.3.0 (2018-01-13)

 * Added: New feature 'Element classes'
 * Fixed: 'Call to a member function hasAccess() on null' on site structure page
 * Fixed: Add 'rel="noopener"' to links with 'target="_blank"'

### 1.2.3 (2017-12-11)

 * Added: Option '_blank' for backend links 
 * Fixed: A problem with BackendUser->authenticate() and the install tool on fresh installations

### 1.2.2 (2017-12-02)

 * Fixed: Wrong paddings of top links
 * Fixed: Missing tooltips for edit multiple buttons in the toolbar
 * Fixed: Edit button not visible, with option 'save buttons'

### 1.2.1 (2017-11-28)

 * Fixed: Array errors (Thanks to Tastaturberuf)
 * Fixed: Some stuff find by phpstorm inspections
 * Fixed: Missing feature in docs
 
### 1.2.0 (2017-11-25)

 * Added: a toolbar button for the contao-manager (only if manager exists)
 * Added: a new feature that dissolve the save- and edit multiple-buttons
 * Added: a new feature that shows an edit layout button in site structure
 * Added: a new feature that make a full width backend possible

### 1.1.1 (2017-09-23)

 * Fixed: a redirect problem in the managed-edition
 * Fixed: a 404 error - modMinus.gif can not be loaded
 * Fixed: backend css for new top line

### 1.1.0 (2017-09-02)

 * Add button 'Create template' to toolbar (see #3).
 * Add feature 'User backend redirects on login' (see #4)