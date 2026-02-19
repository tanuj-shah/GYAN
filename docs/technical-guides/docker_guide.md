# Docker for GYAN Project: Guide & Benefits

## What is Docker?
Docker is a tool that allows you to "package" your entire website—including the code, the version of PHP, the Database server, and all settings—into a single **Container**.

Unlike **XAMPP**, which installs everything directly on your Windows OS, Docker creates isolated environments that behave exactly the same on your computer, a collaborator's computer, or a live cloud server.

---

## 🚀 How Docker Benefits GYAN

### 1. Goodbye "It works on my machine"
Currently, you use XAMPP. If you move to a server that has a different PHP version or different settings, the site might break. With Docker, the **Container** is identical everywhere.

### 2. Isolated Environments
You can run different versions of PHP or MySQL for different projects on the same computer without them interfering with each other.

### 3. Easy "One-Command" Setup
Instead of installing XAMPP and manually configuring databases, a new developer could just run `docker-compose up` and the entire GYAN platform (PHP + MySQL + Apache) would start automatically.

### 4. Direct Cloud Deployment
Most modern cloud providers (Oracle Cloud, AWS, DigitalOcean) prefer Docker. You can push your **Container** instead of just your code, making deployment much faster and safer.

---

## 🛠️ How it would look for GYAN

For our project, we would use a file called `docker-compose.yml`. Here is a simplified concept:

```yaml
version: '3.8'

services:
  # The Web Server (PHP + Apache)
  web:
    image: php:8.2-apache
    volumes:
      - ./public:/var/www/html
    ports:
      - "8080:80"
    environment:
      - DB_HOST=db

  # The Database (MySQL)
  db:
    image: mysql:5.7
    environment:
      MYSQL_DATABASE: gyan_db
      MYSQL_ROOT_PASSWORD: secret_password
```

## 🏁 How to Run GYAN with Docker

If you have **Docker Desktop** installed on your computer, follow these simple steps:

### 1. Start the Environment
Open your terminal (PowerShell or CMD) in the project root and run:
```bash
docker-compose up -d
```
*This will build the containers and start the web server and database in the background.*

### 2. Access the Website
Once started, you can view the site at:
**[http://localhost:8080](http://localhost:8080)**

### 3. Database Initialization
On the first run, Docker will automatically run our `init.sql` script to set up your tables.
- **Web Port**: 8080
- **Database Port**: 3307 (if you want to connect via external tools)

---

## 🛠️ Important Commands

| To... | Run... |
| :--- | :--- |
| **Stop Containers** | `docker-compose stop` |
| **Start Containers** | `docker-compose start` |
| **Rebuild Everything** | `docker-compose up -d --build` |
| **View Logs** | `docker-compose logs -f` |

---

## ⚖️ Docker vs. XAMPP

| Feature | XAMPP | Docker |
| :--- | :--- | :--- |
| **Setup** | Easy installer | requires learning CLI |
| **Consistency** | Low (depends on OS) | High (identical everywhere) |
| **Scaling** | Hard | Very Easy (Cloud Native) |
| **Performance** | Native speed on Windows | Slightly slower on Windows (Virtualization) |

## ✅ Recommendation
For now, **stick with XAMPP** as your project is already working perfectly. However, if you plan to scale GYAN to a larger team or move to high-end cloud hosting, **Docker** is the next logical step for professional engineering.

**© 2026 GYAN - Built for National Impact.**
