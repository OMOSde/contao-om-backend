# Contao bundle change log

### 1.7.5 (2023-04-17)

 + Fixed: Wrong backend user detection

### 1.7.4 (2023-03-28)

 + Fixed: Route prefix detection in Contao 4.9 defect
 + Fixed: One more php 8 issue

### 1.7.3 (2023-03-21)

 + Fixed: Some PHP 8 errors (thx @fritzmg)
 + Fixed: Add some missing parameter handling for `contao.backend.route_prefix`
 + Fixed: Use parameter `contao_manager.manager_path` and `contao.web_dir` for manager detection

### 1.7.2 (2023-02-03)

 + Fixed: Some PHP 8 errors
 + Changed: Deactivate feature `Backend-Tabs` (please remove your this callback in your config.php)

### 1.7.1 (2021-12-17)

 + Fixed: Some PHP warnings

### 1.7.0 (2021-12-16)

 + Improvements for PHP 8
 + Increase system requirements

### 1.6.14 (2021-11-21)

 + Added: Version 4.13 as known lts
 + Added: Replace insert tags in markdown viewer 

### 1.6.13 (2021-05-02)

 + Fixed: Missing if condition on version check

### 1.6.12 (2021-04-28)

 + Fixed: Remove dependency for "wa72/htmlpagedom"

### 1.6.11 (2021-02-12)

 + Fixed: User redirect not working

### 1.6.10 (2021-02-09)

 + Fixed: Backendlinks in Contao 4.9+ not working
 + Fixed: Problem with user rights in Contao 4.9+
 + Fixed: Redirect token problem with id search

### 1.6.9 (2020-09-02)

 + Fixed: Wrong version compare

### 1.6.8 (2020-08-25)

 + Fixed: Overwrite CSS for all links only on backend module sysinfo phpinfo
 + Fixed: Wrong alignment for language on root pages
 
### 1.6.7 (2020-05-22)

 + Added: Add name of layout to title on layout button (see #44)
 + Changed: Replace 'menatwork/contao-multicolumnwizard' with 'menatwork/contao-multicolumnwizard-bundle' (see #34)
 + Fixed: Version feature breaks version info in sysinfo tab packages (see #46)
 + Fixed: Version info shows lower version than current (see #47) 
 + Fixed: Missing error message if no md-File found (see #43)
 + Fixed: Can´t use install tool because redirect to login

### 1.6.6 (2019-12-11)

 + Added: Support rsce-Templates for feature 'Element classes' (see #41)

### 1.6.5 (2019-12-07)

 + Fixed: Hook 'parseTemplate' for element classes not called in frontend (#41) 

### 1.6.4 (2019-11-09)

 + Fixed: Use of 'return;' in config/config.php breaks other extension includes

### 1.6.3 (2019-10-29)

 + Fixed: Do not add features when on install tool
 + Fixed: Some issues with the monolog.logger

### 1.6.2 (2019-10-27)

 + Changed: Show latest versions only if different to current version
 + Added: Log entry for getting contao versions from github

### 1.6.1 (2019-10-18)

 + Fixed: Some issues with version feature

### 1.6.0 (2019-10-18)

 + Added: Show newest Contao version in backend (System settings)

### 1.5.2 (2019-08-31)

 + Fixed: Consider the rights management on create backend module order

### 1.5.1 (2019-07-15)

 + Fixed: Solve an array problem with feature 'Order of backend modules'
 + Fixed: Add missing feature to README.md

### 1.5.0 (2019-07-14)

 + Added: New option to set the order of the backend modules in the system settings

### 1.4.2 (2019-05-22)

 + Changed: Function id-view now searchable and colored, click again shift to remove
 + Changed: Remove dca field 'cssClasses' if no classes assigned
 + Fixed: Replace some used deprecated functions

### 1.4.1 (2019-03-17)

 * Fixed: Backend link problems
 * Fixed: Tables are not correct added to backend modules when use backend tabs
 * Fixed: Some backend link problems with app_dev.php

### 1.4.0 (2019-03-16)

 * Added: Make markdown file editable
 * Added: New feature sysinfo
 * Fixed: Correct language array for id-search

### 1.3.3 (2018-07-14)

 * Fixed: Error feature module-tabs is used without own tables in backend module

### 1.3.2 (2018-07-10)

 * Fixed: A foreach problem with module-tabs 
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