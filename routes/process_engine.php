<?php

// Email notification routes.
Route::post('notifications/{emailKey}/{engineProcessInstanceId}', 'ProcessEngineController@notifications')->name('process-engine.notifications');
