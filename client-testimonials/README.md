# Client Testimonials Plugin

**Version:** 1.0.0
**Author:** Jules
**License:** GPL v2 or later

A WordPress plugin to manage and display client testimonials. It provides a custom post type for testimonials, a star rating system, a shortcode to display testimonials on any page of your active theme, and a REST API endpoint to fetch testimonial data.

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

*   **Custom Post Type:** Adds a "Testimonials" section to your WordPress admin menu for easy management.
*   **Star Rating:** Allows assigning a 1-5 star rating to each testimonial via a meta box on the testimonial edit screen.
*   **Shortcode Display:** Display testimonials on any WordPress page or post using the `[show_testimonials]` shortcode. The plugin provides basic styling for the testimonials, which will integrate with your active theme.
*   **REST API Endpoint:** Provides a public API to fetch all testimonials, suitable for custom integrations or headless setups.

## Usage

### Adding a Testimonial

1.  In your WordPress admin dashboard, navigate to **Testimonials > Add New**.
2.  Enter the **Title** for the testimonial (e.g., Client's Name, Company Name, or a brief highlight).
3.  Write the full testimonial content in the main **editor** area.
4.  On the right-hand side of the editor screen, find the **Star Rating** meta box. Select a rating from 1 to 5 stars.
5.  Click the **Publish** button.

### Displaying Testimonials on a Page

To display your testimonials on your website:

1.  **Create or Edit a Page:**
    *   In your WordPress admin, go to `Pages > Add New` to create a new page, or `Pages > All Pages` to edit an existing one.
    *   Give your page a relevant title (e.g., "Client Reviews", "Testimonials").
2.  **Insert the Shortcode:**
    *   In the WordPress editor for that page, add a new Shortcode block.
    *   Inside the block, type the following shortcode: `[show_testimonials]`
    *   You can add any other content (text, images) above or below the shortcode block as needed.
3.  **Publish or Update the Page.**

The `[show_testimonials]` shortcode will render a list of all your published client testimonials, ordered by date (newest first). Each testimonial will display its title, the main content, and its star rating (if provided). The plugin includes CSS to ensure the testimonials are presented clearly.

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
  // ... more testimonials
]
```

*   `id`: The WordPress Post ID of the testimonial.
*   `title`: The title of the testimonial.
*   `content`: The testimonial text (HTML content, as entered in the editor).
*   `rating`: The star rating (integer, 1-5), or `null` if no rating is set or if the rating is 0.
*   `date`: The publication date of the testimonial in ISO 8601 format (GMT).

## Files and Responsibilities

*   `client-testimonials.php`: The main plugin file. Handles plugin activation, defines constants, includes the class autoloader, and initializes the plugin loader.
*   `includes/`: This directory contains all the PHP classes for the plugin, following PSR-4 naming conventions.
    *   `class-ct-loader.php`: Initializes all core components of the plugin (Post Type, Meta Box, Shortcode, REST API) and registers their respective hooks with WordPress. Also handles loading the plugin's text domain for translations.
    *   `class-ct-post-type.php`: Registers the `client_testimonial` custom post type, defining its labels, supported features (title, editor), menu icon, and other arguments like `publicly_queryable` and ensuring `show_in_rest` is `false` for the CPT itself (as a custom endpoint is provided).
    *   `class-ct-meta-box.php`: Manages the "Star Rating" meta box displayed on the `client_testimonial` edit screen. This includes rendering the select field for the rating, and securely saving the sanitized and validated rating data using nonces and capability checks.
    *   `class-ct-shortcode.php`: Registers the `[show_testimonials]` shortcode. Its callback function queries all published `client_testimonial` posts (ordered by date descending), formats them into semantic HTML (using `div.ct-testimonials` and `div.ct-testimonial`), and ensures all output is properly escaped. It also enqueues the plugin's front-end CSS for styling the testimonials.
    *   `class-ct-rest-api.php`: Registers the custom REST API endpoint `/ct/v1/testimonials`. It defines the route for GET requests, sets a public permission callback, and implements the data callback function. This function queries testimonials and formats them into the specified JSON structure, ensuring all data is sanitized before being returned via `rest_ensure_response()`.
*   `assets/`: Contains front-end assets used by the plugin.
    *   `css/client-testimonials.css`: Provides basic CSS styles for the testimonials list generated by the `[show_testimonials]` shortcode, ensuring a clean presentation.
*   `README.md`: This file. Provides comprehensive information about the plugin, including installation steps, detailed usage instructions for adding testimonials and using the shortcode, and documentation for the REST API endpoint.

## Development Notes

*   The plugin is developed following WordPress Coding Standards and best practices.
*   It uses PSR-4 autoloading for its classes, which are organized within the `CT` namespace.
*   Security is a priority: all external inputs are sanitized and validated (e.g., meta box data, REST API parameters if they were to accept any), and all outputs are escaped (e.g., shortcode HTML, meta box display). Nonces and capability checks are used where appropriate.
*   The plugin relies on WordPress core APIs such as `WP_Query` for data retrieval, post meta functions for handling the star rating, and the WordPress REST API functions for creating the custom endpoint, avoiding direct database queries.
*   Comprehensive PHPDoc blocks are used throughout the code for documenting classes, methods, and functions, aiding in maintainability and understanding.

---

Thank you for using the Client Testimonials plugin!
