# WordPress Docker Development Environment

A simple Docker-based WordPress development environment with MySQL and WP-CLI.

## Features

- WordPress (latest LTS version)
- MySQL 8.0
- WP-CLI for command-line management
- Custom PHP upload limits
- Persistent database storage
- Local wp-content directory for themes and plugins development

## Requirements

- Docker
- Docker Compose

## Setup

1. Clone this repository:
   ```
   git clone <repository-url>
   cd <repository-directory>
   ```

2. Create an `.env` file from the example:
   ```
   cp .env.example .env
   ```
   
   Or manually create an `.env` file with the following contents:
   ```
   MYSQL_DATABASE=wordpress
   MYSQL_USER=wp_user
   MYSQL_PASSWORD=wp_password
   MYSQL_ROOT_PASSWORD=root_password
   ```

3. Start the environment:
   ```
   docker-compose up -d
   ```

4. Access WordPress:
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/wp-admin
   - Default admin credentials (on first install):
     - Username: admin
     - Password: password (change this immediately!)

## Using WP-CLI

You can run WP-CLI commands using the wp-cli service:

```
docker-compose run --rm wp-cli <command>
```

Examples:
```
# List installed plugins
docker-compose run --rm wp-cli plugin list

# Install a plugin
docker-compose run --rm wp-cli plugin install contact-form-7 --activate

# Update WordPress core
docker-compose run --rm wp-cli core update
```

## Development Workflow

### Themes

Place your custom themes in `wp-content/themes/`. They will be automatically available in WordPress.

### Plugins

Place your custom plugins in `wp-content/plugins/`. They will be automatically available in WordPress.

## Stopping the Environment

```
docker-compose down
```

To completely remove volumes (database data will be lost):
```
docker-compose down -v
```

## Customization

- PHP configuration: Edit `uploads.ini` to change PHP settings
- WordPress version: Change the image version in `docker-compose.yml`
- Port mapping: Modify the port in `docker-compose.yml` if 8000 is already in use 