<?php

// Email notification routes.
Route::post('notifications/{processDefinition}/{notification}/{engineProcessInstanceId}', 'ProcessEngineController@notifications')->name('process-engine.notifications');
