# YouTube Playlist Module for Joomla 5

Standalone Joomla 5 module to display a YouTube playlist as a responsive video gallery with a lightbox.  
This module does **not require any plugin** and fetches videos directly using the YouTube Data API.

---

## Features

- Fetch and display videos from a YouTube playlist.
- Responsive grid layout with thumbnails.
- Lightbox popup to play videos.
- Optional video description and metadata (channel, publish date).
- Cache support to reduce API requests.
- Fully standalone module – no dependencies on other plugins.

---

## Requirements

- Joomla **5.x**
- PHP **8.1+**
- A valid **YouTube Data API Key**

---

## Installation

1. Upload and install the module via **Extensions > Manage > Install**.
2. Go to **Extensions > Modules**, click **New** and select **YouTube Playlist**.
3. Configure the module:
   - **Playlist ID** – ID of your YouTube playlist.
   - **Max results** – Number of videos to display (default: 10).
   - **Cache time** – Cache duration in seconds (default: 3600).
   - **Show description** – Enable/disable video description.
4. Assign the module to a menu position or use `{loadposition position_name}` in an article.

---

## Configuration

- **API Key**: Stored in `/configuration/youtube.php` as:

```php
<?php
return [
    'api_key' => 'YOUR_YOUTUBE_API_KEY_HERE'
];
```

- **Cache**: The module caches API responses to minimize requests.
- **Video display**: Each video is shown with a thumbnail, title, optional description, channel, and publish date.

---

## Notes

- This module is fully standalone, ensuring it does not conflict with other modules or plugins.
- Lightbox opens videos inside the module container only.
- Make sure your API Key has YouTube Data API v3 access.
