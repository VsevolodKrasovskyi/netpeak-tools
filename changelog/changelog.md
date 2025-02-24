# 1.0.8
- Fix bugs

---

# 1.0.7

**PLUGIN**
- Refactoring Schema&Structure:
    - Improved AJAX handling for forms, resolving submission and response processing issues.
    - Optimized and refactored schema data saving and retrieval mechanisms.
    - Simplified code structure, making it more readable and easier to debug.
    - Completely refactored the database handler. Enhanced schema and structure functionality to improve data management and overall performance.
    - Fixed issues with the AJAX form handler that caused inconsistencies during data submission.
- Optimized JS and CSS files 
- Added [update check button](https://img.netpeak.net/masik/25-01-31_dNRoC.png), improving the method for updating the plugin

**CDN**
    - Added ability to edit profile
    - Added ability to restore password
---

# 1.0.6
**CDN**
- Added domain and license key validation during key retrieval to enhance security.

**PLUGIN**
- Refactoring the method of loading script from cdn, improving performance and security.
- Refactoring AJAX handlers

---

# 1.0.5
**CDN**  
- Changed the method of key generation: now keys are generated in the format `XXXX-XXXX-XXXX-XXXX-XXXX`.  
- Improvement of internal mechanisms (Account Settings, sending notifications).  

**PLUGINS**  
- Updated plugin update method.  
- Fixed security bugs.  
- Modified the `updater` class for better performance and security.  
- Added a library for reading and converting Markdown files.  
- Visual adjustments to the plugin API for improved user experience.  

---

# 1.0.4
**PLUGIN**  
- Added a widget to the dashboard to display license status.  
- Fixed mail bug, added API debugging.  

**CDN**  
- Updated cdn.netpeak.dev: added a reminder email for license expiration.  

---

# 1.0.3
**PLUGIN**  
- Fixed redirect bug.  

---

# 1.0.2
**PLUGIN**  
- Added a class to update the plugin from the repository.  
- Added a settings page for phpMailer.  

---

# 1.0
Initial plugin release.
