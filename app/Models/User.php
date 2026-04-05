<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'password',
        'tipo',
        'salario',
        'estado',
        'tipodocumento',
        'numerodocumento',
        'avatar_color',
        'avatar_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the URL for the user's profile.
     * This method is required by AdminLTE package.
     */
    public function adminlte_profile_url()
    {
        return route('profile.show');
    }

    /**
     * Get the user's image URL.
     * Returns the uploaded image or a generated avatar with the user's name.
     */
    public function adminlte_image()
    {
        // Si el usuario tiene una imagen subida, retornar esa
        if ($this->avatar_image) {
            return asset('storage/avatars/' . $this->avatar_image);
        }

        // Si no, generar avatar con el color guardado
        $name = urlencode($this->name . ' ' . ($this->apellidos ?? ''));
        $color = $this->avatar_color === 'random' ? 'random' : $this->avatar_color;
        return "https://ui-avatars.com/api/?name={$name}&background={$color}&size=100&bold=true&rounded=true";
    }

    /**
     * Get avatar URL for profile view.
     */
    public function getAvatarUrl()
    {
        if ($this->avatar_image) {
            return asset('storage/avatars/' . $this->avatar_image);
        }

        $name = urlencode($this->name . ' ' . ($this->apellidos ?? ''));
        $color = $this->avatar_color === 'random' ? 'random' : $this->avatar_color;
        return "https://ui-avatars.com/api/?name={$name}&background={$color}&size=300&bold=true&rounded=true";
    }

    /**
     * Get the user's description/subtitle for the usermenu.
     */
    public function adminlte_desc()
    {
        return ucfirst($this->tipo ?? 'Usuario');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'idempleado');
    }
    
}
