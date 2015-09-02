# ACF Plus

Common utility classes for the Advanced Custom Fields (Pro) plugin on WordPress.

## Usage

### Field Groups

Extend the `\logoscon\ACF\Group` class, defining the `_register()` method:

```php
class My_Group extends \logoscon\ACF\Group {

    protected function _register() {
        // My field group definition.
    }

}
```

This method should contain (at the very least) the code exported by Advanced Custom Fields, although you're encouraged to improve its maintainability.  To help make code clearer, the following helper methods are provided:

* `_field_tab`
* `_location_is`
* `_location_in` (see `\logoscon\ACF\Rule\Operator\In`)

### Support for the `IN` operator in location rules

```php
\logoscon\ACF\Rule\Operator\In::register();
```
