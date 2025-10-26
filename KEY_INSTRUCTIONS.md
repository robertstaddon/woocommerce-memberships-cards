# SSH Key Setup Instructions

## 🔑 Keys Generated

Two key files have been created in this directory:
- `github-actions-key` (PRIVATE KEY - keep secret!)
- `github-actions-key.pub` (PUBLIC KEY - safe to share)

## ⚠️ IMPORTANT: Keep Private Key Secret!

The private key (`github-actions-key`) should NEVER be committed to Git or shared publicly.

## 📋 Setup Instructions

### Step 1: Add Public Key to cPanel

1. Log into your cPanel
2. Go to **Security** → **SSH Access**
3. Click **Manage SSH Keys**
4. Click **Import Key**
5. Paste this PUBLIC key:

```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIApTGhtvYKNW9FUPrrlvvk8mvvclmj+2fIXWfzyZ3yrp github-actions-woocommerce-memberships-cards
```

6. Click **Import**
7. Find the imported key and click **Authorize**

### Step 2: Add Private Key to GitHub Secrets

1. Go to your GitHub repository
2. Navigate to **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Name: `SSH_PRIVATE_KEY`
5. Value: Paste the PRIVATE key (the entire key from `github-actions-key` file, including BEGIN and END lines)
6. Click **Add secret**

### Step 3: Add `.gitignore` Entry

Make sure these files are NOT committed to Git. Add to `.gitignore`:

```
github-actions-key
github-actions-key.pub
```

## ✅ Verification

After setup, the keys will allow GitHub Actions to deploy via rsync automatically!

