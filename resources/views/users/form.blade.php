{!! IkForm::model($user, ['files' => true]) !!}

{!! ikForm::checkbox("online", "Activer le compte", $user->online, [
    'default' => 1,
    "desc" => "Si vous n'activez pas le compte, l'administrateur ne pourra plus se connecter."
]) !!}
{!! IkForm::text("firstname", "Prénom", $user->firstname) !!}
{!! IkForm::text("lastname", "Nom", $user->lastname) !!}
{!! IkForm::email("email", "Email", $user->email) !!}
{!! IkForm::password("password", "Mot de passe", $user->email, [
    'desc' => "Laissez vide si pas de changement"
]) !!}
{!! IkForm::select("role", "Rôle", config("ikcms.adminRoles")) !!}

{!! IkForm::submit() !!}

{!! IkForm::close() !!}
