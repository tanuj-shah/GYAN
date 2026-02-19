# The Beginner's Complete Guide: Host Your GYAN Website on Oracle Cloud

This guide is written in plain English. If you follow these 8 parts, your website will be live on a professional server for $0.

---

## Part 1: Getting Your Free Server (Signup)
Oracle Cloud gives you a "Virtual Private Server" (VPS). This is like having a second computer in the cloud that never turns off.

1.  **Sign Up**: Go to [Oracle Cloud Free Tier](https://www.oracle.com/cloud/free/). 
    *   *Why?* You need a professional account. It requires a credit card for ID, but you won't be charged.
2.  **Create an "Instance"**: This is your server.
    *   **Shape**: Select **VM.Standard.A1.Flex**. Change the RAM to **24 GB**.
    *   **OS**: Choose **Ubuntu**.
    *   **Save Key**: This is the most important step. Click **"Save Private Key"**. You will get a file like `private_key.key`. This is your "Password."

## Part 2: Connecting from Windows (The Key)
Before you can talk to your server, Windows needs to "lock" your key file so it's extra secure.

1.  **Place the file**: Move your `private_key.key` to your **Documents** folder.
2.  **Lock the file**: 
    - Right-click the `private_key.key` file > **Properties**.
    - Go to **Security** tab > **Advanced**.
    - Click **Disable inheritance** > **Remove all inherited permissions**.
    - Click **Add** > **Select a principal** > Type your Windows name (e.g., Tanuj) > **Check Names** > **OK**.
    - Check the **Read** box and click OK.
3.  **Open the Server**: Search your computer for "CMD" (Command Prompt). Type this and hit Enter:
    `ssh -i "C:\Users\YourName\Documents\private_key.key" ubuntu@YOUR_IP_ADDRESS`

## Part 3: Opening the Firewall (The Gates)
Imagine your server is a castle. We need to open the gates for web traffic.

1.  **On the Oracle Web Website**: 
    - Go to **Networking** > **Virtual Cloud Networks**.
    - Click your VCN > **Security Lists**.
    - Add **Ingress Rule**: In "Destination Port", type `80, 443`.
2.  **On the Black Command Screen (CMD)**: Paste these one by one:
    ```bash
    sudo iptables -I INPUT 6 -p tcp --dport 80 -j ACCEPT
    sudo iptables -I INPUT 6 -p tcp --dport 443 -j ACCEPT
    sudo netfilter-persistent save
    ```

## Part 4: Choice A - Manual Installation (LAMP)
"LAMP" stands for Linux, Apache, MySQL, and PHP. Run this to install the basics:
```bash
sudo apt update && sudo apt install apache2 mysql-server php php-mysql libapache2-mod-php php-gd php-curl -y
```

*(See previous versions of this doc for detailed manual setup of Apache and MySQL)*

---

## Part 4: Choice B - The Modern Way (Docker) 🚀
This is the recommended way if you want a professional, unbreakable setup.

### 1. Install Docker on your Server
Run these commands one by one to install Docker and Docker Compose:
```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo apt install docker-compose -y
```

### 2. Upload Your Code
Upload your files to `/home/ubuntu/gyan`. (Use Git or SFTP).

### 3. Launch the Containers
Navigate to your project folder and run:
```bash
cd /home/ubuntu/gyan
sudo docker-compose up -d
```
*This will automatically start your Web Server and Database using the configurations we built.*

### 4. Open Port 8080
Since our Docker is set to port **8080**, make sure to open it in your Oracle Cloud Ingress Rules (Part 3) and IPTables:
```bash
sudo iptables -I INPUT 6 -p tcp --dport 8080 -j ACCEPT
sudo netfilter-persistent save
```

---

## Part 7: Making it Fast with Cloudflare
1. **Register Domain**: Add your site to [Cloudflare](https://www.cloudflare.com/).
2. **Update Nameservers**: Link your domain provider.
3. **Turn on SSL/TLS**: Set to **Full**.
4. **Origin Rule**: In Cloudflare, tell it to talk to your server on port **8080** (if using Docker).

---

## Part 8: Important Pro Tips
*   **Logs**: For Docker, type `sudo docker-compose logs -f`.
*   **The .env file**: Always create your `.env` file!
*   **Security**: Docker containers are isolated, making your server harder to hack.

> [!TIP]
> You now have a professional server with **24GB of RAM**. Using Docker makes this setup "Cloud Native," allowing you to scale GYAN to thousands of members with ease.
