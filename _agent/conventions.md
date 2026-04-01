# VTM Project Conventions

## Admin Components Rule
When developing features for the admin dashboard, you MUST use the following standardized components if the functionality is required. Avoid implementing ad-hoc editors, file uploads, or SEO sections.

### 1. WYSIWYG Editor
- **Path**: `resources/views/admin/components/editor.blade.php`
- **Usage**:
  ```blade
  @include('admin.components.editor', [
      'name'   => 'content',        // Input name
      'value'  => $model->content, // Initial value
      'height' => 450              // Optional height (default 380)
  ])
  ```
- **Description**: A comprehensive editor with support for formatting, links, images, and tables.

### 2. Media Picker (Image/File Upload)
- **Path**: `resources/views/admin/components/media-picker.blade.php`
- **Step 1: Include the component once in your page/layout**
  ```blade
  @include('admin.components.media-picker')
  ```
- **Step 2: Trigger the picker from your script or button**
  ```javascript
  openMediaPicker('input_id', function(url, item) {
      console.log('Selected image URL:', url);
      // Your custom logic here
  }, false); // Set to true for multiple selection
  ```
- **Description**: Connects to the central media library, supports folders, search, and instant uploads.

### 3. SEO Checklist
- **Path**: `resources/views/admin/components/seo-checklist.blade.php`
- **Usage**:
  ```blade
  @include('admin.components.seo-checklist', [
      'context' => 'post', // context: post, product, page, etc.
      'model'   => $model // The model containing SEO data
  ])
  ```
- **Description**: Provides a real-time SEO analysis, robots meta configuration, and Schema.org settings (Auto/Manual JSON-LD).

## Maintenance
- Do not modify these core components unless necessary for global improvements.
- If you find a bug in a component, fix it in the component itself rather than hacking around it in your feature view.
