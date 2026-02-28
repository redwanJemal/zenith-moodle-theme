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
\$CFG->dbpass    = '${MOODLE_DOCKER_DBPASS:-moodle_password}';
\$CFG->prefix    = 'mdl_';
\$CFG->dboptions = array(
    'dbpersist' => 0,
    'dbport' => 3306,
    'dbcollation' => 'utf8mb4_unicode_ci',
);

\$CFG->wwwroot   = '${MOODLE_WWWROOT:-https://lms.endlessmaker.com}';
\$CFG->dataroot  = '${MOODLEDATA}';
\$CFG->admin     = 'admin';
\$CFG->directorypermissions = 0777;

// SSL proxy (behind Traefik with HTTPS termination)
\$CFG->sslproxy = true;

// Production settings
\$CFG->themedesignermode = false;
\$CFG->cachejs = true;
\$CFG->langstringcache = true;

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
        --fullname="${MOODLE_SITE_NAME:-Zenith LMS}" \
        --shortname="${MOODLE_SITE_SHORTNAME:-zenith}" \
        --adminuser="${MOODLE_ADMIN_USER:-admin}" \
        --adminpass="${MOODLE_ADMIN_PASS:-Admin123!}" \
        --adminemail="${MOODLE_ADMIN_EMAIL:-admin@endlessmaker.com}" \
        || echo "Installation may have already been completed."
    echo "Moodle installation complete!"
else
    echo "Moodle database already installed ($TABLES tables found)."
    # Run upgrade if needed (e.g. after theme update)
    echo "Checking for pending upgrades..."
    cd /var/www/html
    php admin/cli/upgrade.php --non-interactive || echo "No upgrades pending."
fi

# Fix permissions
chown -R www-data:www-data /var/www/moodledata 2>/dev/null || true
chown -R www-data:www-data /var/www/html/theme/zenith 2>/dev/null || true

echo "Starting Apache..."
exec apache2-foreground
