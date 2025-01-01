<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;

class ImagePolicy
{
    public function view(User $user, Image $image)
    {
        // verificar se o usuário tem acesso ao certificado relacionado
        // return $user->id === $image->certificate->user_id 
        //     || $user->hasRole('admin'); // Se você usar sistema de roles
        
        // Por enquanto, permitir acesso a todos usuários autenticados
        return true;
    }
}