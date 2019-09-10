<?php

Route::post('/users', 'UserController@register');

Route::group([
    'prefix' => 'auth',
], function () {

    Route::post('/signup', 'Api\Auth\SignUpController@signUp');
    Route::post('/login', 'Api\Auth\LoginController@login');
    Route::post('/recovery', 'Api\Auth\ForgotPasswordController@sendResetEmail');
    Route::post('/reset', 'Api\Auth\ResetPasswordController@resetPassword');

});

Route::group([
    'prefix'     => 'auth',
    'middleware' => ['jwt.auth']
], function () {

    Route::post('recovery', 'Api\Auth\ForgotPasswordController@sendResetEmail');
    Route::post('reset', 'Api\Auth\ResetPasswordController@resetPassword');

    Route::post('logout', 'Api\Auth\LogoutController@logout');
    Route::post('refresh', 'Api\Auth\RefreshController@refresh');

});
