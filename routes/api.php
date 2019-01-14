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

    // Learning Products resource routes.
    Route::resource('learning-products', 'LearningProductController', [
        // Override default parameters formatting so that they use camelcase.
        'parameters' => ['learning-products' => 'learningProduct']
    ]);

    // Organizational Unit routes.
    Route::get('organizational-units', 'OrganizationalUnitController@index')->name('organizational-units.index');
    Route::get('organizational-units/{organizationalUnit}/edit', 'OrganizationalUnitController@edit')->name('organizational-units.edit');
    Route::put('organizational-units/{organizationalUnit}', 'OrganizationalUnitController@update')->name('organizational-units.update');

    // Process definition routes.
    Route::get('process-definitions/{entityType}', 'ProcessDefinitionController@show')->name('process-definitions.show');
    Route::post('process-definitions/{processDefinition}', 'ProcessDefinitionController@startProcess')->name('process-definitions.start-process');

    // Process instance routes.
    Route::get('process-instances', 'ProcessInstanceController@index')->name('process-instances.index');
    Route::get('process-instances/{id}', 'ProcessInstanceController@show')->name('process-instances.show');
    Route::put('process-instances/{id}/cancel', 'ProcessInstanceController@cancel')->name('process-instances.cancel');

    // Process instance form routes.
    Route::get('process-instance-forms/{processInstanceFormData}', 'ProcessInstanceFormController@show')->name('process-instance-forms.show');
    Route::put('process-instance-forms/{processInstanceForm}/claim', 'ProcessInstanceFormController@claim')->name('process-instance-forms.claim');
    Route::put('process-instance-forms/{processInstanceForm}/unclaim', 'ProcessInstanceFormController@unclaim')->name('process-instance-forms.unclaim');
    Route::put('process-instance-forms/{processInstanceForm}/release', 'ProcessInstanceFormController@release')->name('process-instance-forms.release');
    Route::put('process-instance-forms/{processInstanceFormData}/edit', 'ProcessInstanceFormController@edit')->name('process-instance-forms.edit');
    Route::put('process-instance-forms/{processInstanceFormData}/submit', 'ProcessInstanceFormController@submit')->name('process-instance-forms.submit');

    // List entities routes.
    Route::get('lists/{entityType}', 'ListController@show')->name('lists.show');
    Route::get('lists', 'ListController@showMultiple')->name('lists.show-multiple');

    // Information sheets routes.
    Route::get('information-sheets/{entityType}/{entityId}', 'InformationSheetController@show')->name('information-sheets.show');

    /**
     * Authorization routes.
     */

    // Project authorization routes.
    // @todo: Refactor into one generic route? i.e. authorization/{entityType}/{action}
    Route::get('authorization/project/create', 'AuthorizationController@createProject')->name('authorization.project.create');
    Route::get('authorization/project/edit/{project}', 'AuthorizationController@editProject')->name('authorization.project.edit');
    Route::get('authorization/project/delete/{project}', 'AuthorizationController@deleteProject')->name('authorization.project.delete');
    Route::get('authorization/project/{project}/start-process/{processDefinition}', 'AuthorizationController@startProjectProcess')->name('authorization.project.start-process');

    // Learning Product authorization routes.
    Route::get('authorization/learning-product/create', 'AuthorizationController@createLearningProduct')->name('authorization.learning-product.create');
    Route::get('authorization/learning-product/delete/{learningProduct}', 'AuthorizationController@deleteLearningProduct')->name('authorization.learning-product.delete');
    Route::get('authorization/learning-product/edit/{learningProduct}', 'AuthorizationController@editLearningProduct')->name('authorization.learning-product.edit');

    // Process instance authorization routes.
    Route::get('authorization/process-instance/cancel/{processInstance}', 'AuthorizationController@cancelProcessInstance')->name('authorization.process-instance.cancel');

    // Process instance form authorization routes.
    Route::get('authorization/process-instance-form/claim/{processInstanceForm}', 'AuthorizationController@claimProcessInstanceForm')->name('authorization.process-instance-form.claim');
    Route::get('authorization/process-instance-form/unclaim/{processInstanceForm}', 'AuthorizationController@unclaimProcessInstanceForm')->name('authorization.process-instance-form.unclaim');
    Route::get('authorization/process-instance-form/release/{processInstanceForm}', 'AuthorizationController@releaseProcessInstanceForm')->name('authorization.process-instance-form.release');
    Route::get('authorization/process-instance-form/edit/{processInstanceForm}', 'AuthorizationController@editProcessInstanceForm')->name('authorization.process-instance-form.edit');
    Route::get('authorization/process-instance-form/submit/{processInstanceForm}', 'AuthorizationController@submitProcessInstanceForm')->name('authorization.process-instance-form.submit');

});
