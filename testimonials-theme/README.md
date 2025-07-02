# Testimonials Theme

**Theme Name:** Testimonials Theme
**Version:** 1.0.0
**Author:** Jules
**License:** GNU General Public License v2 or later

A minimal WordPress theme designed to work with the **Client Testimonials** plugin. It provides a clean and simple page template to showcase client testimonials.

## Installation

1.  **Prerequisite:** Ensure you have the **Client Testimonials** plugin installed and activated. This theme relies on the plugin for testimonial functionality.
2.  **Download:** Download the `testimonials-theme.zip` file.
3.  **Upload via WordPress Admin:**
    *   Navigate to `Appearance > Themes` in your WordPress dashboard.
    *   Click on `Add New` at the top.
    *   Click on `Upload Theme`.
    *   Choose the `testimonials-theme.zip` file and click `Install Now`.
    *   Activate the theme.
4.  **Manual Installation (FTP):**
    *   Unzip `testimonials-theme.zip`.
    *   Upload the `testimonials-theme` folder to the `/wp-content/themes/` directory on your server.
    *   Navigate to `Appearance > Themes` in your WordPress dashboard and activate the "Testimonials Theme".

## Usage

This theme is very minimal and primarily provides a dedicated page template for displaying testimonials managed by the "Client Testimonials" plugin.

### Setting up the Testimonials Page

1.  **Create a New Page:**
    *   In your WordPress admin dashboard, navigate to `Pages > Add New`.
    *   Give your page a title (e.g., "Our Testimonials", "What Clients Say").
2.  **Assign the Page Template:**
    *   In the Page Attributes meta box (usually on the right side of the editor), find the "Template" dropdown.
    *   Select **"Testimonials Page"** from the dropdown list.
3.  **Publish the Page.**

Once published, this page will automatically display the testimonials using the `[show_testimonials]` shortcode provided by the Client Testimonials plugin. The theme's `page-testimonials.php` template handles calling this shortcode.

### Customization

*   **Styling:** The theme includes a basic `style.css` which you can modify or extend using a child theme for more advanced customizations. The Client Testimonials plugin also provides its own basic styles for the shortcode output, which this theme complements.
*   **Menus:** The theme registers one menu location, "Primary Menu". You can assign a menu to this location via `Appearance > Menus`.
*   **Custom Logo & Background:** Supports WordPress core features like Custom Logo and Custom Background, configurable via the Customizer (`Appearance > Customize`).

## Files and Responsibilities

*   `style.css`: Main stylesheet. Contains the theme information header and basic CSS rules for a minimal layout.
*   `functions.php`: Theme setup file.
    *   Defines theme constants.
    *   Sets up theme defaults (text domain, title tag, post thumbnails, nav menus, HTML5 support, custom logo, custom background).
    *   Enqueues the main stylesheet (`style.css`).
    *   Provides very basic `get_header()` and `get_footer()` implementations for the minimal structure.
*   `page-testimonials.php`: Custom page template named "Testimonials Page".
    *   This template is designed to display testimonials.
    *   It calls `get_header()` and `get_footer()`.
    *   It runs a standard WordPress loop to display the page title and content (if any).
    *   Crucially, it executes `do_shortcode('[show_testimonials]')` to render the testimonials list from the Client Testimonials plugin.
*   `README.md`: This file. Provides information about the theme, installation, and usage.

## Notes

*   This theme is intentionally minimal to focus on the integration with the Client Testimonials plugin.
*   For any advanced features or layout changes, consider creating a child theme or using a more feature-rich theme.

---

Thank you for using the Testimonials Theme!
