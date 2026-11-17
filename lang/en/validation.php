<?php

return [
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be text.',
    'email' => 'The :attribute must be a valid email address.',
    'min' => ['string' => 'The :attribute must be at least :min characters.', 'numeric' => 'The :attribute must be at least :min.'],
    'max' => ['string' => 'The :attribute may not be greater than :max characters.', 'numeric' => 'The :attribute may not be greater than :max.', 'file' => 'The :attribute may not be greater than :max KB.'],
    'integer' => 'The :attribute must be a whole number.',
    'date' => 'The :attribute is not a valid date.',
    'date_format' => 'The :attribute format is invalid.',
    'after_or_equal' => 'The :attribute must not be before :date.',
    'exists' => 'The selected :attribute is invalid.',
    'in' => 'The selected :attribute is invalid.',
    'image' => 'The :attribute must be an image.',
    'url' => 'The :attribute format is invalid.',
    'regex' => 'The :attribute format is invalid.',
    'array' => 'The :attribute is invalid.',
    'boolean' => 'The :attribute is invalid.',
    'attributes' => [
        'name' => 'name', 'phone' => 'WhatsApp number', 'email' => 'email', 'service_id' => 'service',
        'staff_id' => 'staff', 'date' => 'date', 'time' => 'time', 'notes' => 'notes', 'password' => 'password',
        'title' => 'title', 'body' => 'body', 'excerpt' => 'excerpt', 'duration_minutes' => 'duration',
        'business_name' => 'business name', 'start_date' => 'start date', 'end_date' => 'end date',
        'logo' => 'logo', 'photo' => 'photo', 'cover' => 'cover', 'brand_color' => 'brand color',
    ],
];
