# 🐬 MySQL + phpMyAdmin Docker Setup

A simple and clean Docker setup for local MySQL development with phpMyAdmin, featuring custom themes, initialization scripts, and organized folder structure.

## 🎯 Features

- **One-command setup** with Docker Compose
- **Custom dark theme** for phpMyAdmin
- **Automatic database initialization** with SQL scripts
- **Persistent data storage** with Docker volumes
- **Secure configuration** with environment variables
- **Production-ready** MySQL configuration

## 📦 Project Structure

```
mysql-docker-setup/
├── .env                      # Environment variables (passwords, etc.)
├── docker-compose.yml        # Main Docker Compose configuration
├── pma.Dockerfile           # Custom phpMyAdmin container build
│
├── initdb/                  # Auto-executed SQL scripts on first run
│   ├── init-database.sql    # Database creation (optional)
│   └── init-user.sql        # User creation and permissions
│
├── mysql-config/
│   └── mysql.cnf           # Custom MySQL configuration
│
├── pma-config/
│   ├── apache.conf         # Apache ServerName configuration
│   └── config.user.inc.php # phpMyAdmin custom settings
│
└── theme-pma/
    └── boodark-orange/     # Custom dark theme for phpMyAdmin
```

## 🚀 Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/mysql-docker-setup.git
cd mysql-docker-setup
```

### 2. Configure Environment Variables

Create a `.env` file in the root directory:

```bash
# MySQL Configuration
MYSQL_ROOT_PASSWORD=your_super_secret_password

# Additional MySQL settings (optional)
# MYSQL_DATABASE=your_database_name
# MYSQL_USER=your_username
# MYSQL_PASSWORD=your_user_password
```

### 3. Start the Services

```bash
docker compose up -d --build
```

### 4. Access phpMyAdmin

Open your browser and navigate to:

```
http://localhost
```

**Login credentials:**

- **Username:** `root`
- **Password:** Value from `MYSQL_ROOT_PASSWORD` in your `.env` file

## 🔧 Configuration

### MySQL Configuration

Custom MySQL settings can be added to `mysql-config/mysql.cnf`. The default configuration includes:

- UTF-8 character set
- Optimized buffer settings
- Custom query cache settings

### phpMyAdmin Themes

The setup includes a custom dark theme (`boodark-orange`). To add more themes:

1. Download theme files to the `theme-pma/` directory
2. Add volume mapping in `docker-compose.yml`:
   ```yaml
   volumes:
     - ./theme-pma/your-theme:/var/www/html/themes/your-theme
   ```
3. Restart the containers
4. Select the theme from phpMyAdmin's appearance settings

### Database Initialization

SQL scripts in the `initdb/` directory are automatically executed when the MySQL container starts for the first time:

- `init-database.sql` - Creates additional databases
- `init-user.sql` - Creates users and sets permissions

## 📊 Services

| Service    | Port | Description                    |
| ---------- | ---- | ------------------------------ |
| MySQL      | 3306 | Database server                |
| phpMyAdmin | 80   | Web-based MySQL administration |

## 🔄 Common Commands

### View Running Containers

```bash
docker compose ps
```

### View Logs

```bash
# All services
docker compose logs

# Specific service
docker compose logs mysql
docker compose logs phpmyadmin
```

### Stop Services

```bash
docker compose down
```

### Complete Reset (⚠️ Deletes all data)

```bash
docker compose down -v --remove-orphans
docker compose up -d --build
```

### Backup Database

```bash
docker compose exec mysql mysqldump -u root -p your_database_name > backup.sql
```

### Restore Database

```bash
docker compose exec -i mysql mysql -u root -p your_database_name < backup.sql
```

## 🐛 Troubleshooting

### Port Already in Use

If ports 3306 or 80 are already in use, modify the port mappings in `docker-compose.yml`:

```yaml
ports:
  - "3307:3306" # Change 3306 to 3307 for MySQL
  - "8080:80" # Change 80 to 8080 for phpMyAdmin
```

### Permission Issues

If you encounter permission issues:

```bash
sudo chown -R $USER:$USER ./mysql-data
```

### Container Won't Start

Check the logs for detailed error messages:

```bash
docker compose logs mysql
```

## 🔒 Security Notes

- Change default passwords in the `.env` file
- Don't commit the `.env` file to version control
- Use strong passwords for production environments
- Consider using Docker secrets for production deployments

## 📋 Requirements

- Docker Engine 20.10+
- Docker Compose 2.0+
- At least 512MB available RAM
- 2GB available disk space

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 💫 Credits

**Built by:** Rizal Suryawan  
**Powered by:** Docker 🐳, MySQL 🐬, and phpMyAdmin  
**Theme:** Custom dark theme for better developer experience 😎
