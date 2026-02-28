#!/bin/bash
set -e

CONFIGFILE="/var/www/html/config.php"
MOODLEDATA="/var/www/moodledata"

# Wait for database to be ready
echo "Waiting for database..."
for i in $(seq 1 30); do
    if php -r "
        \$conn = @new mysqli('${MOODLE_DOCKER_DBHOST}', '${MOODLE_DOCKER_DBUSER}', '${MOODLE_DOCKER_DBPASS}', '${MOODLE_DOCKER_DBNAME}');
        if (\$conn->connect_error) { exit(1); }
        \$conn->close();
        exit(0);
    " 2>/dev/null; then
        echo "Database is ready!"
        break
    fi
    echo "  Attempt $i/30 - waiting..."
    sleep 2
done

# Generate config.php if it doesn't exist
if [ ! -f "$CONFIGFILE" ]; then
    echo "Generating Moodle config.php..."
    cat > "$CONFIGFILE" <<MOODLECONFIG
<?php
unset(\$CFG);
global \$CFG;
\$CFG = new stdClass();

\$CFG->dbtype    = 'mariadb';
\$CFG->dblibrary = 'native';
\$CFG->dbhost    = '${MOODLE_DOCKER_DBHOST:-mariadb}';
\$CFG->dbname    = '${MOODLE_DOCKER_DBNAME:-moodle}';
\$CFG->dbuser    = '${MOODLE_DOCKER_DBUSER:-moodle}';
\$CFG->dbpass    = '${MOODLE_DOCKER_DBPASS:-moodle_dev}';
\$CFG->prefix    = 'mdl_';
\$CFG->dboptions = array(
    'dbpersist' => 0,
    'dbport' => 3306,
    'dbcollation' => 'utf8mb4_unicode_ci',
);

\$CFG->wwwroot   = 'http://localhost:8081';
\$CFG->dataroot  = '${MOODLEDATA}';
\$CFG->admin     = 'admin';
\$CFG->directorypermissions = 0777;

// Enable theme designer mode for development
\$CFG->themedesignermode = true;
// Disable caching for development
\$CFG->cachejs = false;

require_once(__DIR__ . '/lib/setup.php');
MOODLECONFIG
    chown www-data:www-data "$CONFIGFILE"
    echo "config.php generated."
fi

# Run Moodle install if needed (check if DB tables exist)
TABLES=$(php -r "
    \$conn = new mysqli('${MOODLE_DOCKER_DBHOST}', '${MOODLE_DOCKER_DBUSER}', '${MOODLE_DOCKER_DBPASS}', '${MOODLE_DOCKER_DBNAME}');
    \$result = \$conn->query('SHOW TABLES');
    echo \$result->num_rows;
    \$conn->close();
" 2>/dev/null || echo "0")

if [ "$TABLES" -eq 0 ] 2>/dev/null; then
    echo "Running Moodle installation (this takes 2-3 minutes)..."
    cd /var/www/html
    php admin/cli/install_database.php \
        --agree-license \
        --fullname="Zenith Dev" \
        --shortname="zenith" \
        --adminuser=admin \
        --adminpass="Admin123!" \
        --adminemail="admin@example.com" \
        || echo "Installation may have already been completed."
    echo "Moodle installation complete!"
else
    echo "Moodle database already installed ($TABLES tables found)."
fi

# Fix permissions (only moodledata, NOT theme/ — that's bind-mounted from host)
chown -R www-data:www-data /var/www/moodledata 2>/dev/null || true

echo "Starting Apache..."
exec apache2-foreground
