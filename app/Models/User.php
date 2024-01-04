<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param Request $request
     * @return Collection
     */
    public function admins(int $club_id): Collection
    {
        return DB::table('users')
            ->select('id', 'name')
            ->where('club_id', '=', $club_id)
            ->get();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function UsersFind($searchUser, $club_id)
    {
        return Client::where('login', 'ILIKE', '%'.$searchUser.'%')->where('club_id', $club_id)->get()->toArray();
    }

    public function getPermissions($user_id, $club_id)
    {
        return UserAccess::where('club_id', $club_id)->where('user_id', $user_id)->first();
    }

    /**
     * @param mixed $email
     * @param mixed $club_id
     * @param mixed $login
     * @param mixed $phone
     * @param mixed $lastName
     * @param mixed $firstName
     * @param mixed $middleName
     * @param string $passwordHash
     * @param mixed $birthDate
     * @param mixed $idUserSetting
     * @return void
     */
    public function userAdd(mixed $email, mixed $club_id, mixed $login, mixed $phone, mixed $lastName, mixed $firstName, mixed $middleName, string $passwordHash, mixed $birthDate, mixed $idUserSetting): void
    {
        DB::transaction(function () use ($idUserSetting, $birthDate, $passwordHash, $middleName, $firstName, $lastName, $phone, $login, $club_id, $email) {
            $id_user = DB::table('users')->insertGetId([
                'email' => $email,
                'club_id' => $club_id,
                'name' => $login,
                'phone' => $phone,
                'surname' => $lastName,
                'name_auth' => $firstName,
                'middle_name' => $middleName,
                'password' => $passwordHash,
                'birthday' => $birthDate,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $userSetting = UserSetting::find($idUserSetting);
            $userSetting->user_id = $id_user;
            $userSetting->activation = true;
            $userSetting->save();
        });
    }

    /**
     * @param mixed $email
     * @return JsonResponse|string[]
     */
    public function emailVerify(mixed $email): array|JsonResponse
    {
        $user = User::where('email', $email)->first();
        if ($user == null) {
            $data = array('status' => 'OK');
            return $data;
        } else {
            return response()->json([
                'status' => 'Email уже существует',
            ], 400);
        }
    }

    /**
     * @param mixed $club_id
     * @return mixed
     */
    public function getUserAndPosition(mixed $club_id)
    {
        return User::where('users.club_id', $club_id)->leftJoin('position', 'position.id', 'users.position_id')->get(['users.id', 'users.name', 'users.icon']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getUserByClubId($club_id)
    {
        return User::where('club_id', $club_id)->get();
    }
}
