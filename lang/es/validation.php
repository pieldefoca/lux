<?php

return [
    'user' => [
        'currentPassword' => [
            'required' => 'Escribe tu contraseña actual',
            'current_password' => 'La contraseña no es correcta',
        ],
        'email' => [
            'required' => 'Escribe un email',
            'email' => 'Escribe un email válido',
        ],
        'name' => [
            'required' => 'Escribe un nombre',
        ],
        'password' => [
            'confirmed' => 'Las contraseñas no coinciden',
            'required' => 'Escribe una contraseña',
        ],
        'username' => [
            'required' => 'Escribe un nombre de usuario',
            'unique' => 'Ya existe un usuario con ese nombre',
        ],
    ]
];