# Falcon X Investment Platform - Setup Guide

This guide will help you set up the Falcon X Investment Platform locally.

## 1. Prerequisites
Ensure you have the following installed:
-   **PHP** (8.2 or higher)
-   **Composer**
-   **Node.js** & **NPM**
-   **MySQL** or **SQLite**

## 2. Installation

### Step 1: Clone and Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install
```

### Step 2: Environment Setup
Duplicate the example environment file and configure it:
```bash
cp .env.example .env
php artisan key:generate
```
Open `.env` and configure your database settings:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 3: Database & Seeding (CRITICAL)
Run the migrations and seeders to set up the database tables, admin user, and initial wallet pool.
```bash
php artisan migrate --seed
```
**This will create the following default accounts:**
-   **Admin User**: `admin@example.com` / `password`
-   **Regular User**: `user@example.com` / `password`

## 3. Scheduler Setup (For Bot Profits)
The AI Trading Bots require the Laravel Scheduler to run every minute to generate profits.
**Local Development:**
Run this command in a separate terminal window:
```bash
php artisan schedule:work
```
**Production:**
Add the following Cron entry to your server:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## 4. Running the Application
Start the development servers:
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite (Frontend)
npm run dev
```
Access the app at `http://localhost:8000`.

## 5. Feature Guide

### 👑 Admin Features
-   **Dashboard**: Manage users, wallets, and withdrawals.
-   **Add Wallets**: Go to `/admin/wallets` to add new deposit addresses for users to use.
-   **Manage Withdrawals**: Approve or reject withdrawal requests at `/admin/withdrawals`.
-   **Review KYC**: Approve/Reject user ID submissions at `/admin/kyc-review`.

### 🤖 AI Trading Bots
1.  **Subscribe**: Users can go to **Invest** and purchase a bot plan (e.g., "Pro Algo").
2.  **Profit Generation**: Once subscribed, the bot will automatically generate profit every ~4-24 hours depending on the plan.
    -   *Force Test*: Run `php artisan bot:simulate-trades` to trigger a trade immediately.
3.  **Activity Log**: Users can view their bot's performance in **Activity > Bot Logs**.

### 🆔 KYC Verification
1.  Users must complete KYC in **Settings** before withdrawing.
2.  Admins must approve the KYC application.
3.  Once verified, withdrawals are unlocked.

### 💰 Deposits & Withdrawals
-   **Deposits**: Users select a wallet (BTC/USDT), send funds, and their balance is updated (currently mocked/manual approval).
-   **Withdrawals**: Users request a withdrawal; Admins must approve it for funds to be deducted.

## 6. Testing Tips
**Simulating Successful Deposits:**
-   When entering a transaction hash for a manual deposit, use any string starting with `0xconfirmed` (e.g., `0xconfirmed123`).
-   Click "Check Status" -> The system will instantly verify and credit your balance for testing purposes.
