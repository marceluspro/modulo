<?php


Route::prefix('usertype')->as('usertype.')->middleware(['auth'])->group(function () {
    Route::get('list', 'UserTypeController@index')->name('list')->middleware('RoutePermissionCheck:usertype.list');
    Route::get('list-data', 'UserTypeController@data')->name('list-data');
    Route::post('setting', 'UserTypeController@settingSubmit')->name('setting')->middleware('demo');;
    Route::post('assign-org', 'UserTypeController@assignOrg')->name('assignOrg')->middleware('demo');
    Route::get('change-panel/{role_id}', 'UserTypeController@changePanel')->name('changePanel');
});
