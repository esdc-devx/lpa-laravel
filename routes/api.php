<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Translations route.
Route::get('locales', 'LocaleController@index');

Route::middleware('auth:api')->group(function () {

    // User resource routes.
    Route::get('users/current', 'UserController@current')->name('users.current');
    Route::get('users/search', 'UserController@search')->name('users.search');
    Route::resource('users', 'UserController');

    // Project resource routes.
    Route::resource('projects', 'ProjectController');

    // Process definition routes.
    Route::get('process-definitions/{entityType}', 'ProcessDefinitionController@show')->name('process-definitions.show');
    Route::post('process-definitions/{processDefinition}', 'ProcessDefinitionController@startProcess')->name('process-definitions.start-process');

    // Process instance routes.
    Route::get('process-instances/{id}', 'ProcessInstanceController@show')->name('process-instances.show');

    // Process instance form routes.
    Route::get('process-instance-forms/{processInstanceForm}', 'ProcessInstanceFormController@show')->name('process-instance-forms.show');

    // List entities routes.
    Route::get('lists/{entityType}', 'ListController@show')->name('lists.show');
    Route::get('lists', 'ListController@showMultiple')->name('lists.show-multiple');

    // Project authorization routes.
    // @todo: Refactor into one generic route? i.e. authorization/{entityType}/{action}
    Route::get('authorization/project/create', 'AuthorizationController@createProject')->name('authorization.project.create');
    Route::get('authorization/project/edit/{project}', 'AuthorizationController@editProject')->name('authorization.project.edit');
    Route::get('authorization/project/delete/{project}', 'AuthorizationController@deleteProject')->name('authorization.project.delete');
    Route::get('authorization/project/{project}/start-process/{processDefinition}', 'AuthorizationController@startProjectProcess')->name('authorization.project.start-process');
});
