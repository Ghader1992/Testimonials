# Client Testimonials Plugin

**Version:** 1.0.0
**Author:** Jules
**License:** GPL v2 or later

A WordPress plugin to manage and display client testimonials. It provides a custom post type for testimonials, a star rating system, a shortcode to display testimonials, and a REST API endpoint to fetch testimonial data.

## Installation

1.  **Download:** Download the `client-testimonials.zip` file.
2.  **Upload via WordPress Admin:**
    *   Navigate to `Plugins > Add New` in your WordPress dashboard.
    *   Click on `Upload Plugin`.
    *   Choose the `client-testimonials.zip` file and click `Install Now`.
    *   Activate the plugin through the 'Plugins' menu in WordPress.
3.  **Manual Installation (FTP):**
    *   Unzip `client-testimonials.zip`.
    *   Upload the `client-testimonials` folder to the `/wp-content/plugins/` directory on your server.
    *   Activate the plugin through the 'Plugins' menu in WordPress.

## Features

*   **Custom Post Type:** Adds a "Testimonials" section to your WordPress admin menu.
*   **Star Rating:** Allows assigning a 1-5 star rating to each testimonial via a meta box.
*   **Shortcode:** Display testimonials on any page or post using `[show_testimonials]`.
*   **REST API Endpoint:** Provides a public API to fetch all testimonials.

## Usage

### Adding a Testimonial

1.  In your WordPress admin dashboard, navigate to **Testimonials > Add New**.
2.  Enter the **Title** (e.g., Client's Name or Company).
3.  Write the testimonial content in the main **editor**.
4.  On the right-hand side, in the **Star Rating** meta box, select a rating from 1 to 5 stars.
5.  Click **Publish**.

### Using the Shortcode

To display your testimonials on a page, post, or widget that supports shortcodes:

1.  Edit the page/post where you want the testimonials to appear.
2.  Add a new Shortcode block (or use the classic editor's text mode).
3.  Insert the following shortcode: `[show_testimonials]`
4.  Save or update the page/post.

The shortcode will output a list of all published testimonials, ordered by date (newest first), including their title, content, and star rating.

### Consuming the REST API

The plugin exposes a public REST API endpoint to retrieve all published testimonials. This is useful for developers who want to display testimonials in custom applications or headless WordPress setups.

*   **Endpoint URL:** `/wp-json/ct/v1/testimonials`
*   **Method:** `GET`
*   **Authentication:** None required (publicly accessible).

**Example Response (JSON):**

```json
[
  {
    "id": 123,
    "title": "Amazing Service!",
    "content": "<p>This company provided excellent service from start to finish. Highly recommend.</p>",
    "rating": 5,
    "date": "2024-07-15T10:30:00"
  },
  {
    "id": 120,
    "title": "Good Value",
    "content": "<p>The product offers good value for the price. Satisfied with my purchase.</p>",
    "rating": 4,
    "date": "2024-07-10T14:20:00"
  }
]
```

*   `id`: The WordPress Post ID of the testimonial.
*   `title`: The title of the testimonial (e.g., client's name).
*   `content`: The testimonial text (HTML content).
*   `rating`: The star rating (integer, 1-5), or `null` if no rating is set.
*   `date`: The publication date of the testimonial in ISO 8601 format (GMT).

## Files and Responsibilities

*   `client-testimonials.php`: The main plugin file. Handles plugin activation, loading dependencies, and initializing the plugin. Defines constants and includes the autoloader.
*   `includes/`: This directory contains all the PHP classes for the plugin.
    *   `class-ct-loader.php`: Initializes all components of the plugin (post type, meta box, shortcode, REST API) and hooks them into WordPress. Handles text domain loading.
    *   `class-ct-post-type.php`: Registers the `client_testimonial` custom post type with its specific labels, supports, and settings (e.g., menu icon, `show_in_rest = false`).
    *   `class-ct-meta-box.php`: Adds, renders, and saves the "Star Rating" meta box on the testimonial edit screen. Includes nonce security, user capability checks, input sanitization, and validation.
    *   `class-ct-shortcode.php`: Registers and renders the `[show_testimonials]` shortcode. Queries testimonials, formats them in semantic HTML, and ensures all output is escaped. Also enqueues front-end CSS for basic styling.
    *   `class-ct-rest-api.php`: Registers the custom REST API endpoint `/ct/v1/testimonials`. Handles GET requests, queries testimonials, formats the data into the required JSON structure, and ensures data sanitization and public access.
*   `assets/`: Contains front-end assets.
    *   `css/client-testimonials.css`: Basic CSS styles for the testimonials displayed by the shortcode.
*   `README.md`: This file. Provides information about the plugin, installation, and usage.

## Development Notes

*   The plugin follows WordPress Coding Standards and uses PSR-4 autoloading for its classes within the `CT` namespace.
*   All external inputs are sanitized and validated, and all outputs are escaped.
*   Uses WordPress APIs (WP_Query, post meta functions, REST API functions) instead of direct database queries.
*   PHPDoc blocks are used for documenting classes and methods.

---

Thank you for using the Client Testimonials plugin!
