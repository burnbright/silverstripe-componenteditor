# SilverStripe Component Editor

A light-weight control for editing a single object, using it's `getFrontEndFields`. Useful for one-to-one (has_one, belongs_to) relationships.

A new object will be created on first save.
Subsequently the form will be populated with the object data, and saved back to that object.

## Usage

Update your DataObject as follows:

 * Implement/extend the `getFrontEndFields` method to control form fields.
 * Implement/extend the `canCreate`, `canEdit`, and `canDelete` functions to control access. [see docs](http://doc.silverstripe.org/framework/en/reference/modeladmin#permissions).

Add the `EditComponentForm` inside your controller class:
```php
<?php
    public function Form() {
        //returns an empty object, if not aready present
        $testimonial = $this->owner->getMember()->Testimonial();
        return new EditComponentForm($this->owner, "Form", $testimonial);
    }
?>
```


## Troubleshooting / future work

Currently works best if your object (eg Testimonial) contains the has_one relationship, if you are wanting to join to the parent (eg Member). This is because the EditComponentForm only saves the object you are working on, rather than the parent.
Swap your has_one, with your belongs_to to resolve saving issues.
Alternatively, save and link the new object before passing to the form (could end up creating a lot of empty objects).
There is potential for this module to be upgraded to handle has_one relationships, perhaps by introducing a callback, or requiring that parent object be passed in.