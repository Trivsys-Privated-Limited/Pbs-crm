<?php
use App\Http\Controllers\adminController;
use App\Http\Controllers\AdvanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\payrollController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ResignationController;
use App\Http\Controllers\userController;
use App\Http\Middleware\validRole;
use App\Http\Middleware\validUser;
use App\Http\Middleware\CheckOfficeIP;
use Illuminate\Support\Facades\Route;

Route::middleware(CheckOfficeIP::class)->group(function () {
Route::controller(dashboardController::class)->group(function () {
    Route::get('/dashboard', 'viewDashboard')->name('dashboard')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/viewAgentSaleTable', 'viewAgentSaleTable')->name('viewAgentSaleTable')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/viewAgentLeadlTable', 'viewAgentLeadlTable')->name('viewAgentLeadlTable')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/viewAgentTrialTable', 'viewAgentTrialTable')->name('viewAgentTrialTable')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/update_customer', 'cutomerUPdateDetailFormVIew')->name('cutomerUPdateDetailFormVIew')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/cutomerUPdateDetailStore', 'cutomerUPdateDetailStore')->name('cutomerUPdateDetailStore')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/cutomerUPdateDetailSaleStore', 'cutomerUPdateDetailSaleStore')->name('cutomerUPdateDetailSaleStore')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/cutomerUPdateDetailTrialStore', 'cutomerUPdateDetailTrialStore')->name('cutomerUPdateDetailTrialStore')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/cutomerUPdateSaleDetailFormVIew', 'cutomerUPdateSaleDetailFormVIew')->name('cutomerUPdateSaleDetailFormVIew')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/cutomerUPdateTrialDetailFormVIew', 'cutomerUPdateTrialDetailFormVIew')->name('cutomerUPdateTrialDetailFormVIew')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/deleteLeadCustomerDetails', 'deleteLeadCustomerDetails')->name('deleteLeadCustomerDetails')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/deleteSaleCustomerDetails', 'deleteSaleCustomerDetails')->name('deleteSaleCustomerDetails')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/deleteTrialCustomerDetails', 'deleteTrialCustomerDetails')->name('deleteTrialCustomerDetails')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/updateCustomerStatus', 'updateCustomerStatus')->name('updateCustomerStatus')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/deleteCustomerDetails', 'deleteCustomerDetails')->name('deleteCustomerDetails')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/Help-Request', 'viewHelpRequestTableDashboard')->name('viewHelpRequestTableDashboard')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/downHelpRequestStatus', 'downHelpRequestStatus')->name('downHelpRequestStatus')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/viewTrialDaysForm', 'viewTrialDaysForm')->name('viewTrialDaysForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/update-customer-status', 'updateStatusCustomerTrial')->name('updateStatusCustomerTrial')->middleware(validUser::class);
    Route::get('/dashboard/{id}/view-update-customer-status', 'viewupdateSaleCustomerStatus')->name('viewupdateSaleCustomerStatus')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/update-customer-sale-status', 'updateSaleCustomerStatus')->name('updateSaleCustomerStatus')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/add-customer-sale-day', 'viewSaleDaysForm')->name('viewSaleDaysForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/addSaleCustomerStatus', 'addSaleCustomerStatus')->name('addSaleCustomerStatus')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/customer-numbers', 'viewCustomerNumber')->name('viewCustomerNumber')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/add-customer-numbers', 'viewCustomerNumberForm')->name('viewCustomerNumberForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/storeCustomerNumbers', 'storeCustomerNumbers')->name('storeCustomerNumbers')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/all-numbers', 'viewNumbersTable')->name('viewNumbersTable')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/add-numbers', 'viewAddNumbersForm')->name('viewAddNumbersForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/storeNumbers', 'storeNumbers')->name('storeNumbers')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/customer-response', 'viewAgentDistributeNumbersDetail')->name('viewAgentDistributeNumbersDetail')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/all-agent-sale-reports/{id}/', 'viewSaleTable')->name('viewSaleTable')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/all-agent-lead-reports/{id}/', 'viewleadtable')->name('viewleadtable')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/distribute-lead/{id}/', 'distributeLeadsForm')->name('distributeLeadsForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/updateLeadAgent/{id}', 'updateLeadAgent')->name('updateLeadAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/distribute-single-lead', 'distributeSingleLeadForm')->name('distributeSingleLeadForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/updateSingleLeadAgent', 'updateSingleLeadAgent')->name('updateSingleLeadAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/distribute-number', 'distributeNumberForm')->name('distributeNumberForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/distributeNumberToAgent', 'distributeNumberToAgent')->name('distributeNumberToAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/distributesaleToAgent', 'viewAgentDistributeSale')->name('viewAgentDistributeSale')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/updateSaleAgent', 'updateSaleAgent')->name('updateSaleAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/distribute-single-sale', 'distributeSingleSaleForm')->name('distributeSingleSaleForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/updateSingleSaleAgent', 'updateSingleSaleAgent')->name('updateSingleSaleAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/all-agent-trial-report', 'viewtrialtable')->name('viewtrialtable')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/distributeTrialsForm', 'distributeTrialsForm')->name('distributeTrialsForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/distribute-single-trial', 'distributeSingleTrialForm')->name('distributeSingleTrialForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/updateSingleTrialAgent', 'updateSingleTrialAgent')->name('updateSingleTrialAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/updateTrialAgent', 'updateTrialAgent')->name('updateTrialAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/all-agent-sale', 'filterSaleByDate')->name('filterSaleByDate')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/all-peding-sale', 'viewPendingSale')->name('viewPendingSale')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/acceptPendingSale', 'acceptPendingSale')->name('acceptPendingSale')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/mac_expiry', 'viewMacExpiryData')->name('viewMacExpiryData')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/add_sale', 'viewAddNewAgentSaleForm')->name('viewAddNewAgentSaleForm')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/save_sale', 'saveNewAgentSale')->name('saveNewAgentSale')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/old_number', 'viewOldNumber')->name('viewOldNumber')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/dis_old_number', 'disOldCustomerNumberToAgent')->name('disOldCustomerNumberToAgent')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/dis_old_number', 'storeOldCustomerNumber')->name('storeOldCustomerNumber')->middleware(validUser::class)->middleware(validRole::class);
    Route::post('/dashboard/{id}/updateHelpRequeststatus', 'updateHelpRequeststatus')->name('updateHelpRequeststatus')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/all-leave-recode', 'viewAllLeaveRecode')->name('viewAllLeaveRecode')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/{id}/viewRenewalpage', 'viewRenewalpage')->name('viewRenewalpage')->middleware(validUser::class)->middleware(validRole::class);
    Route::get('/dashboard/notServiceNumber', 'notServiceNumber')->name('notServiceNumber')->middleware(validUser::class)->middleware(validRole::class);
});

Route::controller(userController::class)->middleware(validUser::class)->middleware(validRole::class)->group(function () {
    Route::get('/dashboard/all-user', 'viewUserTable')->name('viewUserTable');
    Route::get('/dashboard/add-user', 'addUser')->name('addUser');
    Route::get('/dashboard/{id}/update-user', 'viewEditForm')->name('viewEditFormUser');
    Route::post('/dashboard/storeUserdetail', 'storeUserdetail')->name('storeUserdetail');
    Route::post('/dashboard/{id}/storeUpdateUser', 'storeUpdateUser')->name('storeUpdateUser');
    Route::get('/dashboard/{id}/deleteUser', 'deleteUser')->name('deleteUser');
    Route::get('/dashboard/sendMail', 'sendMail')->name('sendMail');
    Route::get('/dashboard/{id}/viewUserChangePassword', 'viewUserChangePassword')->name('viewUserChangePassword');
    Route::post('/dashboard/{id}/changeAgentPasswordStore', 'changeAgentPasswordStore')->name('changeAgentPasswordStore');
});

Route::controller(payrollController::class)->middleware(validUser::class)->middleware(validRole::class)->group(function () {
    Route::get('/dashboard/emplyee-payroll', 'index')->name('payroll.index');
    Route::get('/dashboard/add-payroll', 'create')->name('payroll.create');
    Route::post('/dashboard/store-payroll', 'store')->name('payroll.store');
    Route::get('/dashboard/{id}/show/', 'show')->name('payroll.show');
    Route::get('/dashboard/{id}/showPayroll/', 'showPayroll')->name('payroll.showPayroll');
});

Route::controller(EmployeController::class)->middleware(validUser::class)->middleware(validRole::class)->group(function () {
    Route::get('/dashboard/manage-employee', 'index')->name('employee.index');
    Route::get('/dashboard/add-employee', 'create')->name('employee.create');
    Route::post('/dashboard/store-employee', 'store')->name('employee.store');
    Route::get('/dashboard/{id}/view-employee', 'show')->name('employee.show');
    Route::get('/dashboard/{id}/edit-employee', 'edit')->name('employee.edit');
    Route::post('/dashboard/{id}/update-employee', 'update')->name('employee.update');
});

Route::controller(AttendanceController::class)->middleware(validUser::class)->middleware(validRole::class)->group(function () {
    Route::get('/dashboard/employee-attendance', 'index')->name('attendance.index');
    Route::get('/dashboard/add-attendance', 'create')->name('attendance.create');
    Route::get('/dashboard/employee-attendance/{employee_name}', 'show')->name('attendance.show');
    Route::post('/dashboard/store-attendance', 'store')->name('attendance.store');
    Route::get('/dashboard/import-attendance', 'importAttendance')->name('attendance.import');
    Route::post('/dashboard/import-attendance', 'importAttendanceStore')->name('attendance.import.store');
});

Route::controller(SupportController::class)->middleware(validUser::class)->middleware(validRole::class)->group(function () {
    Route::get('/dashboard/import', 'index')->name('support.import');
    Route::post('/dashboard/import', 'store')->name('support.import.store');
});

Route::controller(AdvanceController::class)->middleware(validUser::class)->middleware(validRole::class)->group(function () {
    Route::get('/dashboard/manage-advance', 'index')->name('advance.index');
    Route::get('/dashboard/add-advance', 'create')->name('advance.create');
    Route::get('/dashboard/show/{id}', 'show')->name('advance.show');
    Route::post('/dashboard/store-advance', 'store')->name('advance.store');
});

Route::controller(LeaveController::class)->middleware(validUser::class)->group(function () {
    Route::get('/leave-list', 'index')->name('leave.index');
    Route::get('/request-to-leave', 'create')->name('leave.create');
    Route::post('/store-leave-request', 'store')->name('leave.store');
    Route::get('/leave/approve/{id}', 'approve')->name('approveLeave');
    Route::post('/leave/reject/{id}', 'reject')->name('rejectLeave');
});

Route::controller(ResignationController::class)->middleware(validUser::class)->group(function () {
    Route::get('/manage-resignations', 'index')->name('resignation.index');
    Route::get('/request-to-resignation', 'create')->name('resignation.create');
    Route::post('/store-resignation-request', 'store')->name('resignation.store');
    Route::get('/resignation/accept/{id}', 'accept')->name('resignation.accept');
    Route::get('/resignation/reject/{id}', 'reject')->name('resignation.reject');
    Route::get('/resignation-letter/{id}', 'show')->name('resignation.show');
});

Route::get('/user/{id}/activate', [UserController::class, 'activateUser'])->name('activateUser');
Route::get('/user/{id}/deactivateUser', [UserController::class, 'deactivateUser'])->name('deactivateUser');

Route::controller(adminController::class)->middleware(validUser::class)->middleware(validRole::class)->group(function () {
    Route::get('/dashboard/all-admin', 'viewAdminTable')->name('viewAdminTable');
    Route::get('/dashboard/add-admin', 'viewAddForm')->name('viewAddForm');
    Route::get('/dashboard/{id}/edit-admin', 'viewEditForm')->name('viewEditFormAdmin');
    Route::post('/dashboard/storeAdminDetail', 'storeAdminDetail')->name('storeAdminDetail');
    Route::post('/dashboard/{id}/storeUpdateAdmin', 'storeUpdateAdmin')->name('storeUpdateAdmin');
    Route::get('/dashboard/{id}/deleteAdmin', 'deleteAdmin')->name('deleteAdmin');
    Route::get('/dashboard/change-password', 'viewAdminUpdatePasswordForm')->name('viewAdminUpdatePasswordForm');
    Route::post('/dashboard/{id}/changePasswordStore', 'changePasswordStore')->name('changePasswordStore');
});

Route::controller(homeController::class)->group(function () {
    Route::get('/', 'viewHome')->name('viewHome')->middleware(validUser::class);
});

Route::controller(CustomerController::class)->group(function () {
    Route::post('/storeCustomerDetail', 'storeCustomerDetail')->name('storeCustomerDetail');
    Route::post('/customerStatus/{id}', 'customerStatus')->name('customerStatus');
    Route::get('/customerSalesTable', 'customerSalesTable')->name('customerSalesTable')->middleware(validUser::class);
    Route::get('/customerLeadTable', 'customerLeadTable')->name('customerLeadTable')->middleware(validUser::class);
    Route::get('/customerTrialTable', 'customerTrialTable')->name('customerTrialTable')->middleware(validUser::class);
    Route::get('/customer-numbers', 'viewCunstomerNumberTable')->name('viewCunstomerNumberTable')->middleware(validUser::class);
    Route::post('/{id}/storeCustomerNumbersDetails', 'storeCustomerNumbersDetails')->name('storeCustomerNumbersDetails')->middleware(validUser::class);
    Route::get('/{id}/edit-customer-numbers', 'viewCustomerNumberEditForm')->name('viewCustomerNumberEditForm')->middleware(validUser::class);
    Route::get('/{id}/add-old-customer-data', 'viewOldCustomerNewPKG')->name('viewOldCustomerNewPKG')->middleware(validUser::class);
    Route::post('/{id}/storeOldCustomerNewPKGData', 'storeOldCustomerNewPKGData')->name('storeOldCustomerNewPKGData')->middleware(validUser::class);
    Route::post('/{id}/storeCustomerNumberEditDetails', 'storeCustomerNumberEditDetails')->name('storeCustomerNumberEditDetails')->middleware(validUser::class);
    Route::get('/{id}/edit-sale', 'viewEditCustomerSaleDetailForm')->name('viewEditCustomerSaleDetailForm')->middleware(validUser::class);
    Route::post('/{id}/storeEditCustomerSaleDetails', 'storeEditCustomerSaleDetails')->name('storeEditCustomerSaleDetails')->middleware(validUser::class);
    Route::get('/{id}/update-lead', 'viewleadEditForm')->name('viewleadEditForm')->middleware(validUser::class);
    Route::get('/{id}/update-trial', 'viewTrialEditForm')->name('viewTrialEditForm')->middleware(validUser::class);
    Route::get('/customerDeniedTable', 'customerDeniedTable')->name('customerDeniedTable')->middleware(validUser::class);
    Route::post('/{id}/storeUpdateLeadData', 'storeUpdateLeadData')->name('storeUpdateLeadData')->middleware(validUser::class);
    Route::post('/{id}/storeUpdateTrialData', 'storeUpdateTrialData')->name('storeUpdateTrialData')->middleware(validUser::class);
    Route::get('/getAllCallingNumbers', 'getAllCallingNumbers')->name('getAllCallingNumbers')->middleware(validUser::class);
    Route::get('/SaleExpiry', 'viewSaleExpiry')->name('viewSaleExpiry')->middleware(validUser::class);
    Route::post('/SaleExpiry/{id}/update', 'updateSaleExpiry')->name('updateSaleExpiry')->middleware(validUser::class);
    Route::get('/{id}/viewUpdateSaleExpiryForm', 'viewUpdateSaleExpiryForm')->name('viewUpdateSaleExpiryForm')->middleware(validUser::class);
    Route::get('/supportNumbers', 'supportNumbers')->name('supportNumbers')->middleware(validUser::class);
    Route::get('/daniyalNumbers', 'daniyalNumbers')->name('daniyalNumbers')->middleware(validUser::class);
    Route::get('/saadNumbers', 'saadNumbers')->name('saadNumbers')->middleware(validUser::class);
    Route::post('/{id}/storeSupportNumber', 'storeSupportNumber')->name('storeSupportNumber')->middleware(validUser::class);
});

Route::controller(HelpController::class)->group(function () {
    Route::get('/help-Request', 'viewHelpForm')->name('help')->middleware(validUser::class);
    Route::get('/help-Detail', 'viewHelpTable')->name('viewHelpTable')->middleware(validUser::class);
    Route::get('/update/{id}/remarks', 'updateRemarksForm')->name('updateRemarksForm')->middleware(validUser::class);
    Route::post('/storeHelpRequest', 'storeHelpRequest')->name('storeHelpRequest')->middleware(validUser::class);
    Route::post('/agent/{id}/remarks-update', 'agentRemarksUpdate')->name('agentRemarksUpdate')->middleware(validUser::class);
});

Route::get('/employee/profile', [EmployeController::class, 'viewEmployeeProfile'])->name('employee.profile');


});

Route::controller(userController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/storeLogin', 'loginstore')->name('loginstore');
// Verify otp route
    Route::get('/verify-otp', 'verifyOtp')->name('verifyOtp');
    Route::post('/verify-otp-store', 'verifyOtpStore')->name('verifyOtpStore');
    Route::get('/logout', 'logout')->name('logout');
});
