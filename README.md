# Offer Ending Soon

A simple, lightweight WordPress plugin to display an evergreen countdown banner on your site. Create a sense of urgency and boost conversions with a continuously resetting offer timer.

## Features

- **Evergreen Countdown Timer**: Set a duration (e.g., 5 hours) and the timer will run for each visitor. Once it expires, it resets automatically and starts again.
- **Session Management**: Uses browser `localStorage` to ensure the countdown stays accurate across page reloads and multiple sessions, bypassing aggressive caching plugins.
- **Customizable Banner**: Configure the offer title, countdown duration, button label, button link, and colors (background and text) directly from the WordPress admin.
- **Flexible Display Options**:
  - Automatically display the banner at the top of your site (requires a theme that supports the `wp_body_open` hook).
  - Use the `[offer_ending_soon]` shortcode to place the banner anywhere on your site (in posts, pages, or widget areas).
- **Responsive Design**: The banner is fully responsive and looks great on all devices.
- **Lightweight**: Minimal CSS and vanilla JavaScript ensure your site remains fast.

## Installation

1. Download the plugin files or clone this repository.
2. Upload the `offer-ending-soon` folder to your `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to **Settings > Offer Ending Soon** to configure your banner.

## Usage

### 1. Configuration

Navigate to **Settings > Offer Ending Soon** in your WordPress dashboard. Here you can configure:

- **Offer Title:** e.g., "Lifetime Subscription 50% Off"
- **Offer Duration (Hours):** The number of hours for the countdown loop.
- **Button Label & Link:** The call to action button text and destination URL.
- **Colors:** Choose your preferred background and text colors.
- **Display Location:** Choose between automatically displaying at the top of the site or using a shortcode only.

### 2. Displaying the Banner

If you selected **Top of Site** in the settings, the banner will automatically appear right below the `<body>` tag, provided your theme implements the `wp_body_open` action hook (standard in modern themes).

If you select **Shortcode Only** or want to place the countdown manually within content, use the following shortcode limit:

```text
[offer_ending_soon]
```

## Changelog

### 1.0.0

- Initial Release
