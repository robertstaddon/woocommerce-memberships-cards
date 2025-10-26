# Release Process

This document explains how to create a new release for WooCommerce Memberships Cards.

## Manual Release Process

### 1. Update Version Numbers

Update the version in these files:
- `woocommerce-memberships-cards.php` - Plugin header
- `README.md` - Changelog section
- `readme.txt` - Stable tag and changelog

### 2. Build the Distribution Package

Run the build script to create the distribution package:

**Windows:**
```bash
build-plugin.bat
```

**Linux/Mac:**
```bash
bash build-plugin.sh
```

This creates: `build/woocommerce-memberships-cards.zip`

### 3. Create Git Tag

Create a version tag:

```bash
git add .
git commit -m "Release version 1.0.4"
git tag v1.0.4
git push origin main
git push origin v1.0.4
```

### 4. Create GitHub Release

1. Go to your repository on GitHub
2. Click **"Releases"** → **"Draft a new release"**
3. Fill in:
   - **Tag:** `v1.0.4`
   - **Title:** `WooCommerce Memberships Cards 1.0.4`
   - **Description:** Copy from the changelog in `readme.txt`
4. Upload the zip file: `build/woocommerce-memberships-cards.zip`
5. Click **"Publish release"**

## Automated Release (Optional)

If you set up GitHub Actions with the workflow in `.github/workflows/release.yml`:

1. Simply push a tag:
   ```bash
   git tag v1.0.4
   git push origin v1.0.4
   ```

2. GitHub Actions will automatically:
   - Build the plugin
   - Create a release
   - Upload the zip file

## Release Checklist

- [ ] Update version numbers in all files
- [ ] Update changelog with new features/bug fixes
- [ ] Test the plugin thoroughly
- [ ] Build the distribution package
- [ ] Verify the zip file works
- [ ] Create git tag
- [ ] Push to GitHub
- [ ] Create GitHub release
- [ ] Upload zip file
- [ ] Test installation on a fresh WordPress site

## Testing the Release Package

Before distributing:

1. Download your own release
2. Install on a fresh WordPress test site
3. Activate the plugin
4. Verify all features work
5. Test on different WooCommerce Memberships configurations

## Version Numbering

Follow [Semantic Versioning](https://semver.org/):
- **MAJOR** (1.0.0): Breaking changes
- **MINOR** (1.1.0): New features, backward compatible
- **PATCH** (1.0.1): Bug fixes, backward compatible

