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

    // Which statuses get the button. Others show nothing in this column
    // (the status column next to it still says reserviert / vermietet).
    'statuses' => ['free', 'reserved'],

    // Our form: the visitor can add more apartments from the list (badges, ?wohnung=
    // preselects one) and must pick at least one. false = only the one from the link.
    'multiple' => true,

    // Fields of our application form, like config/contact.php.
    'fields' => [
        'street' => 'required',
        'zip_city' => 'required',
        'phone' => 'required',
        'message' => 'optional',
    ],

];
