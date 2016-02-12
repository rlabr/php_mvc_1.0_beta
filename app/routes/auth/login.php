<?php

$app->get('/login', function() use ($app) {
  $app->render('auth/login.php');
})->name('login');

$app->post('/login', function() use($app) {
  $request = $app->request;

  $identifier = $request->post('identifier');
  $password = $request->post('password');

  $v = $app->validation;

  $v->validate([
    'identifier' => [$identifier, 'required'],
    'password' => [$password, 'required']
  ]);

  if ($v->passes()) {
    $user = $app->user
            ->where('username', $identifier)
            ->orwhere('email', $identifier)
            ->first();

    if ($user && $app->hash->passwordCheck($password, $user->password)) {
      $_SESSION[$app->config->get('auth.session')] = $user->id;
      $app->flash('global', 'you are now signed in!');
    }else{
      $app->flash('global', 'could not log in!');
    }

    $app->response->redirect($app->urlFor('home'));
  }

  $app->render('auth/login.php', [
    'errors' => $v->errors(),
    'request' => $request
  ]);

})->name('login.post');
