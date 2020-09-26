# SB Schedule

Just a small tool for scheduling for diner appointments.
Feel free to use.

* * *

## Requirements

-   This software needs a Webserver with PHP, MySQL

## Installation

We recommend using git to install SB Business. This makes it very easy to update SB Business.

1.  Go to the directory, where you plan to run SB Schedule in.
2.  Initialize and clone the repository: 

    ```
    git init
    git clone https://github.com/sballinone/sbschedule.git
    ```

_Note: git clone creates a subdirectory called "sbschedule". You may clone your repository to the parent level and symlink your htdocs to sbschedule._

3.  Create a database and import database.sql
    
    ```
    mysql -u <username> -p <database> < database.sql
    ```

4.  Run SB Schedule in your webbrowser.

### Update

1.  To update SB Schedule, just go into your git repository and pull the new updates.

    ```
    git pull
    ```

_Please note: There is always the latest release of SB Business hosted within the repository. We host beta releases on the beta branch._

### Quick Start

-   Run SB Schedule by opening the application in your webbrowser.

## Translation

-   You may add as many languages as you want: Just copy the file ./lang/en.php, paste the new file named with the two letter language code (e.G. fr.php for French) and translate everything after the = operators. Remember not to change vars.

    Example: 

    English: 

        $output["welcome"] = "Welcome ".$\_SESSION["firstname"]; 

    German: 

        $output["welcome"] = "Willkommen ".$\_SESSION["firstname"];

* * *

## Thank you

For this software we used some open source projects:

-   IcoFont - <https://icofont.com>
-   Google Webfonts - <https://fonts.google.com>
-   Google Webfont Helper - <https://google-webfonts-helper.herokuapp.com/fonts>
-   FlexBoxGrid - <https://flexboxgrid.com>

Thank you to the open source community.
