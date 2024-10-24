netpeak-seo/
│
├── admin/
│   ├── components/
│   │   ├── tab-header.php              # EN: Template header file / BG: Файл за хедър на шаблон
│   ├── templates/
│   │   ├── sitemap-html.php            # EN: HTML Sitemap template / BG: Шаблон за HTML карта на сайта
│   │   ├── alt-title-image.php         # EN: Alt Title settings template / BG: Шаблон за настройки на Alt Title
│   │   └── redirect.php                # EN: Redirect settings template / BG: Шаблон за настройки на пренасочване
│   └── menu.php                        # EN: Adds a menu item in the admin / BG: Добавя елемент в менюто на администратора
│
├── inc/
│   ├── functions/
│   │   ├── register-settings.php       # EN: Register plugin options / BG: Регистриране на опции на плъгина
│   ├── tools/                          # EN: Tools for the plugin / BG: Инструменти за плъгина
│   │   ├── alt-title-image.php         # EN: Functions for Alt Title tools / BG: Функции за инструменти Alt Title
│   │   │    ├── auto-alt-title.php     # EN: Automatic Alt Title generation / BG: Автоматично генериране на Alt Title
│   │   │    ├── elementor.php          # EN: Elementor integration / BG: Интеграция с Elementor
│   │   │    └── modules.php            # EN: Additional modules / BG: Допълнителни модули
│   │   ├── redirect.php                # EN: Redirect tool / BG: Инструмент за пренасочване
│   │   ├── sitemap.php                 # EN: Sitemap tool / BG: Инструмент за карта на сайта
│
├── assets/
│   ├── css/
│   │   └── admin.css                   # EN: Admin-specific styles / BG: Административни стилове
│   ├── js/
│   │   ├── dependent-checkbox.js       # EN: JavaScript for dependent checkboxes / BG: JavaScript за зависими чекбоксове
│   │   ├── tooltip.js                  # EN: JavaScript for tooltips / BG: JavaScript за подсказки
│
├── languages/                          # EN: Language files directory / BG: Директория за езикови файлове
│   └── bg_BG.mo                        # EN: Bulgarian translation file / BG: Файл за превод на български
│   └── bg_BG.po
│   └── netpeak-seo.pot                 # EN: POT file for translations / BG: POT файл за преводи
│
├── uninstall.php                       # EN: Plugin uninstall script / BG: Скрипт за деинсталиране на плъгина (да се добави)
├── init.php                            # EN: Constants and include files / BG: Константи и включващи файлове
├── readme.md                           # EN: Plugin documentation / BG: Документация на плъгина
└── netpeak-seo.php                     # EN: Main plugin file with metadata and initialization / BG: Основен файл на плъгина с метаданни и инициализация
