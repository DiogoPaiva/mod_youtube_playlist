# 🎵 YouTube Playlist Module & Plugin

A custom **Joomla 5 module** that displays a list of videos from a specific **YouTube playlist**, using the **YouTube Data API v3** securely via an **AJAX plugin** to avoid exposing the API key.

---

## ✅ Features

- Displays a list of YouTube videos from a specific playlist
- Uses secure AJAX request to fetch data (no API key exposed in frontend)
- Fully compatible with **Joomla 5**
- Configurable through the backend:
  - Playlist ID
  - Max Results

---

## 🛠️ Installation

1. 🔹 Download and extract the ZIP file containing both:

   - The **module**: `mod_youtube_playlist`
   - The **plugin**: `plg_ajax_youtube_playlist`

2. 🔧 Install each extension separately:
   - Go to **Extensions > Manage > Install**
   - Upload and install first the **Plugin**: `plg_ajax_youtube_playlist_v1.0.0.zip`
   - Then upload and install the **Module**: `mod_youtube_playlist_v1.0.0.zip`

---

## ⚙️ Configuration

### 🔹 Step 1: Enable the Plugin

1. Go to **Extensions > Plugins**
2. Search for **"Ajax - YouTube Playlist"**
3. Make sure it is **published**

### 🔹 Step 2: Configure the Module

1. Go to **Content > Modules**
2. Find the **"YouTube Playlist"** module
3. Click on its name to open the configuration
4. Fill in:

   - **Playlist ID**: Your YouTube playlist ID (e.g., `PLABCD1234567890`)
   - **Max Results**: Number of videos to display (default is 5)

5. Choose a **position** (e.g., `custom`)
6. Assign it to the pages where you want it to appear
7. Set **Status** to **Published**
8. Click **Save**

---

## 📄 Usage in Articles

To display the module inside an article, use this tag:

```html
{loadposition custom}
```
