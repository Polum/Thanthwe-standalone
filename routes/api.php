<?php

use Illuminate\Http\Request;

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
// Route::group(['middleware' => 'cors'], function () {
    Route::post('/setup', 'api\v1\SetupController@init');
    Route::post('/login', 'api\v1\UserController@login');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('/logout', 'api\v1\UserController@logout');

        Route::get('/dashboard', 'api\v1\DashboardController@index');

        // API routes for users
        Route::get('/users', 'api\v1\UserController@index');
        Route::post('/users', 'api\v1\UserController@register');
        Route::put('/users', 'api\v1\UserController@update');
        Route::post('/user/edit-password', 'api\v1\UserController@editPassword');
        Route::put('/Susers', 'api\v1\UserController@updateSuper');
        Route::get('/users/{id}', 'api\v1\UserController@show');
        Route::delete('/users/{id}', 'api\v1\UserController@destroy');

        // API routes for roles
        Route::get('/roles', 'api\v1\RoleController@index');
        Route::get('/roles-su', 'api\v1\RoleController@su');

        Route::get('/church', 'api\v1\ChurchController@index');
        Route::put('/church/update', 'api\v1\ChurchController@update');
        Route::delete('/church/{id}', 'api\v1\ChurchController@destroy');
        Route::post('/church/restore', 'api\v1\ChurchController@restore');

        // API routes for homcells
        Route::get('/homecells', 'api\v1\HomecellController@index');
        Route::post('/homecells', 'api\v1\HomecellController@store');
        Route::put('/homecells/{id}', 'api\v1\HomecellController@update');
        Route::delete('/homecells/{id}', 'api\v1\HomecellController@destroy');

        // API routes for zones
        Route::get('/zones', 'api\v1\ZoneController@index');
        Route::post('/zones', 'api\v1\ZoneController@store');
        Route::put('/zones/{id}', 'api\v1\ZoneController@update');
        Route::delete('/zones/{id}', 'api\v1\ZoneController@destroy');

        // API routes for departments
        Route::get('/departments', 'api\v1\DepartmentController@index');
        Route::post('/departments', 'api\v1\DepartmentController@store');
        Route::put('/departments/{id}', 'api\v1\DepartmentController@update');
        Route::delete('/departments/{id}', 'api\v1\DepartmentController@destroy');

        // API routes for ministries
        Route::get('/ministries', 'api\v1\MinistryController@index');
        Route::post('/ministries', 'api\v1\MinistryController@store');
        Route::put('/ministries/{id}', 'api\v1\MinistryController@update');
        Route::delete('/ministries/{id}', 'api\v1\MinistryController@destroy');

        // API routes for committee types
        Route::get('/committee_types', 'api\v1\CommitteeController@index');
        Route::post('/committee_types', 'api\v1\CommitteeController@store');
        Route::put('/committee_types/{id}', 'api\v1\CommitteeController@update');
        Route::delete('/committee_types/{id}', 'api\v1\CommitteeController@destroy');

        // API routes for members
        Route::get('/member', 'api\v1\MemberController@index');
        Route::get('/families', 'api\v1\MemberController@families');
        Route::get('/member/profile/{id}', 'api\v1\MemberController@profile');
        Route::get('/member/{id}', 'api\v1\MemberController@show');
        Route::post('/member', 'api\v1\MemberController@store');
        Route::post('/member_committee', 'api\v1\MemberController@memberCommittee');
        Route::put('/member/{id}', 'api\v1\MemberController@update');
        Route::put('/member/left/{id}/{leave_date}', 'api\v1\MemberController@left');
        Route::post('/member/search', 'api\v1\MemberController@search');
        Route::get('/memberCount', 'api\v1\MemberController@memberCount');
        Route::put('/member/deceased/{id}/{deceased_date}', 'api\v1\MemberController@deceased');

        // API routes for church activities
        Route::get('/activity', 'api\v1\ActivityController@index');
        Route::post('/activity', 'api\v1\ActivityController@store');
        Route::put('/activity/{id}', 'api\v1\ActivityController@update');
        Route::delete('/activity/{id}', 'api\v1\ActivityController@destroy');

        // API routes for homecell activities
        Route::get('/homecell_activity', 'api\v1\HomecellActivityController@index');
        Route::post('/homecell_activity', 'api\v1\HomecellActivityController@store');
        Route::put('/homecell_activity', 'api\v1\HomecellActivityController@update');
        Route::get('/homecell_activity/{id}', 'api\v1\HomecellActivityController@show');
        Route::get('/homecell_activity/attendance/{id}', 'api\v1\HomecellActivityController@attendance');

        // API for homecell activity attendance
        Route::post('/homecell_activity/attendance', 'api\v1\AttendanceController@store');
        Route::delete('/homecell_activity/attendance/{id}', 'api\v1\AttendanceController@destroy');
        Route::get('/member/homecell_attendance_sheet/{id}', 'api\v1\MemberController@attendanceSheet');

        // API routes for Accounts
        Route::get('/account', 'api\v1\AccountController@index');
        Route::post('/account', 'api\v1\AccountController@store');
        Route::get('/account/{id}', 'api\v1\AccountController@show');
        Route::put('/account/{id}', 'api\v1\AccountController@update');
        Route::delete('/account/{id}', 'api\v1\AccountController@destroy');

        // API routes for deposits
        Route::get('/deposit', 'api\v1\DepositController@index');
        Route::post('/deposit', 'api\v1\DepositController@store');

        // API routes for payment vouchers
        Route::post('/payment-voucher', 'api\v1\PaymentVoucherController@store');

        // API routes for expenses
        Route::post('/expense', 'api\v1\ExpenseController@store');

        // API routes for transactions
        Route::get('/transactions', 'api\v1\TransactionController@index');
        Route::put('/transactions/request-reverse/{id}',  'api\v1\TransactionController@requestReverse');
        Route::post('/transactions/reverse',  'api\v1\TransactionController@reversal');

        // API routes for offerings
            // Offering Types
        Route::get('/offering-types', 'api\v1\OfferingTypeController@index');
        Route::post('/offering-types', 'api\v1\OfferingTypeController@store');
        Route::put('/offering-types/{id}', 'api\v1\OfferingTypeController@update');
        Route::delete('/offering-types/{id}', 'api\v1\OfferingTypeController@destory');
            // sunday basket
        Route::post('/sunday-basket', 'api\v1\SundayBasketController@store');
        Route::get('/sunday-basket/{year}/{month}', 'api\v1\SundayBasketController@basketGraph');

        Route::get('/offering_types', 'api\v1\OfferingController@offerType');

        Route::post('/tithe', 'api\v1\TitheController@store');
        Route::get('/tithe/stats', 'api\v1\TitheController@stats');
        Route::get('/tithe/graph_data/{year}', 'api\v1\TitheController@graphData');

            // other offerings
        Route::post('/other-offering', 'api\v1\OfferingController@store');
        Route::get('/other-offering/stats/{year}/{month}', 'api\v1\OfferingController@stats');

        // API routes for reports
        Route::get('/report/{name}/{year}', 'api\v1\ReportController@download');

        // API routes for notifications
        Route::get('/notification/transactions', 'api\v1\TransactionController@reverseRequests');
        Route::get('/notification/transaction-list', 'api\v1\TransactionController@requestList');

        // Reports
        Route::get('/report-stats/{year}', 'api\v1\ReportStatsController@stats');
    });
// });
