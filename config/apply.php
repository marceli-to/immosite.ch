<?php

/*
|--------------------------------------------------------------------------
| Apply per apartment (module:apply)
|--------------------------------------------------------------------------
|
| Button in the apartment table for free and reserved apartments.
| mode: 'auto' = the source's application link (Flatfox/Melon) if there is one,
|       otherwise our form on /bewerben; 'link' = only the source's link;
|       'form' = always our form.
|
*/

return [

    'mode' => 'auto',

    'label' => 'Bewerben',

    // Fields of our application form, like config/contact.php.
    'fields' => [
        'street' => 'required',
        'zip_city' => 'required',
        'phone' => 'required',
        'message' => 'optional',
    ],

];
