<?php

use App\Modules\Settings\Http\Controllers\DocumentSettingsController;
use Illuminate\Support\Facades\Route;


Route::group(array('module' => 'Settings', 'middleware' => ['web', 'auth']), function () {

    Route::get('/settings/get-district-by-division-id', 'SettingsController@get_district_by_division_id');
    Route::get('settings/get-police-stations', 'SettingsController@getPoliceStations');
    Route::get('settings/get-district-user', 'SettingsController@getDistrictUser');
    Route::get('settings/license-data-upload','BulkDataUploadController@licenseDataUploadForm');
    Route::post('settings/upload-bulk-xls','BulkDataUploadController@storeBulkData');
    Route::get('settings/get-bulk-upload-data','BulkDataUploadController@getBulkUploadData' );
    Route::get('settings/bidding-license-configuration','SettingsController@biddingLicenseConfigurationForm');
    Route::post('/settings/store-bidding-license-configuration','SettingsController@storeBiddingLicenseConfiguration');
    Route::get('settings/get-bidding-configured-license-list', 'SettingsController@getBiddingConfiguredLicenseList');
    Route::get('/settings/bidding-configure-license/edit/{id}','SettingsController@editBiddingConfiguredLicense');
    Route::post('/settings/update-bidding-license-configuration/{id}', 'SettingsController@updateBiddingConfiguredLicense');
});

Route::group(array('module' => 'Settings', 'middleware' => ['web']), function () {
    Route::get('settings/get-branch-by-bank-id', 'SettingsController@get_branch_by_bank_id');
});

/* *************************************************
 * All routes for Common OSS Feature
 * Please, Do not write project basis routes here.
 ************************************************* */
Route::group(array('module' => 'Settings', 'middleware' => ['web', 'auth', 'checkAdmin']), function () {


    //****** Maintenance Mode  ****//
    Route::get('settings/maintenance-mode', "SettingsController@maintenanceMode");
    Route::get('settings/maintenance-mode/get-users-list', "SettingsController@getMaintenanceUserList");
    Route::get('settings/maintenance-mode/remove-user/{user_id}', "SettingsController@removeUserFromMaintenance");
    Route::post('settings/maintenance-mode/store', "SettingsController@maintenanceModeStore");


    //*********** Display Device ******************//
    Route::get('settings/display-settings/display-device', 'DisplaySettingsController@displayDeviceList');
    Route::get('settings/display-settings/get-display-device-data', 'DisplaySettingsController@getDisplayDeviceData');
    Route::get('settings/display-settings/create-display-device', 'DisplaySettingsController@createNewDisplayDevice');
    Route::post('settings/display-settings/store-display-device', 'DisplaySettingsController@storeDisplayDevice');
    Route::get('settings/display-settings/edit-display-device/{id}', 'DisplaySettingsController@editDisplayDevice');
    Route::patch('settings/display-settings/update-display-device/{id}', 'DisplaySettingsController@updateDisplayDevice');
    Route::get('settings/display-settings/show-request-history/{id}', 'DisplaySettingsController@showRequestHistoryDevice');


    //*********** Server Info ******************//
    Route::get('settings/server-info', 'ServerInfoController@serverInfo');
//****** Application Rollback  ****//
    Route::get('settings/app-rollback', 'SettingsController@applicationRollbackList');
    Route::post('settings/app-rollback/list', 'SettingsController@getApplicationList');
    Route::get('settings/app-rollback-search', 'SettingsController@applicationSearch');
    Route::post('settings/app-rollback-open', 'SettingsController@applicationRollbackOpen');
    Route::post('settings/app-rollback/update', 'SettingsController@applicationRollbackUpdate');
    Route::post('settings/get-user-by-desk', "SettingsController@getUserByDesk");
    Route::get('settings/app-rollback-view/{id}', 'SettingsController@viewApplicationRollback');


    //****** Forcefully data update update ****//
    Route::get('settings/forcefully-data-update', "SettingsController@forcefullyDataUpdate");
    Route::post('settings/get-forcefully-data-update-data', "SettingsController@getForcefullyDataList");
    Route::get('settings/create-forcefully-data-update', "SettingsController@createForcefullyDataUpdate");
//    Route::get('settings/edit-forcefully-data-update/{id}', "SettingsController@editForcefullyDataUpdate");
    Route::post('settings/store-forcefully-data-update', "SettingsController@storeForcefullyDataUpdate");
    Route::post('settings/approve-forcefully-data-update', "SettingsController@approveForcefullyDataUpdate");
    Route::get('settings/forcefully-data-update-view/{id}', "SettingsController@singleForcefullyViewById");

    //****** Need help ****//
//    Route::get('settings/need-help', 'SettingsController@needHelp');
//    Route::patch('settings/need-help', 'SettingsController@needHelpStore');
//    Route::any('settings/upload-document', 'SettingsController@uploadDocument');

});


/* *************************************************
 * All routes for Project Basis setup
 *
 * Please, write all project basis routes in below
 * and use the ProjectSettingsController.php file
 ************************************************* */
Route::group(array('module' => 'Settings', 'middleware' => ['web', 'auth', 'checkAdmin']), function () {

    //for vue js
    //****** Bank List ****//
    Route::get('settings/bank-list-v2', "SettingsControllerV2@bankv2");
    Route::get('settings/edit-bank-v2/{id}', "SettingsControllerV2@editBankv2");
    Route::post('settings/store-bank-v2', "SettingsControllerV2@storeBankv2");
    Route::get('settings/bank-list-v2-get', "SettingsControllerV2@BankListv2");
    Route::get('settings/bank-list-v2/delete/{id}', "SettingsControllerV2@softDeleteBank");
    Route::post('settings/update-bank-v2/{id}', "SettingsControllerV2@updateBankv2");

    //****** Branch List ****//
    Route::get('settings/branch-list-v2-get', "SettingsControllerV2@BranchListv2");
//    Route::get('settings/create-branch', "SettingsController@createBranch");
    Route::get('settings/edit-branch/{id}', "SettingsControllerV2@editBranch");
//    Route::get('settings/view-branch/{id}', "SettingsController@viewBranch");
    Route::post('settings/store-branch', "SettingsControllerV2@storeBranch");
    Route::post('settings/update-branch/{id}', "SettingsControllerV2@storeAndUpdateBranch");
    Route::get('settings/get-bank-name', "SettingsControllerV2@bankName");

//****** Notice List ****//
    Route::get('settings/notice-list', "SettingsControllerV2@NoticeList");
    Route::post('settings/store-notice', "SettingsControllerV2@storeNotice");
    Route::get('settings/edit-notice/{id}', "SettingsControllerV2@editNotice");
    Route::post('settings/update-notice/{id}', "SettingsControllerV2@updateNotice");

//****** Currency List ****//
    Route::get('settings/currency-list', "SettingsControllerV2@CurrencyList");
    Route::post('settings/store-currency', "SettingsControllerV2@StoreCurrency");
    Route::get('settings/edit-currency/{id}', "SettingsControllerV2@editCurrency");
    Route::patch('settings/update-currency/{id}', "SettingsControllerV2@updateCurrency");

//****** Service List ****//
    Route::get('settings/get-service-name', "SettingsControllerV2@serviceName");
    Route::get('settings/document-list', "SettingsControllerV2@DocumentList");
    Route::post('settings/store-document', "SettingsControllerV2@StoreDocument");
    Route::get('settings/edit-document/{id}', "SettingsControllerV2@editDocument");
    Route::patch('settings/update-document/{id}', "SettingsControllerV2@updateDocument");

//****** Act & Rules List ****//

    Route::get('settings/act-rules-list', "SettingsControllerV2@ActRulesList");
    Route::post('settings/store-act-rules', "SettingsControllerV2@StoreActRules");
    Route::get('settings/edit-act-rules/{id}', "SettingsControllerV2@editActRules");
//    Route::patch('settings/update-act-rules/{id}', "SettingsControllerV2@updateActRules");
    Route::post('settings/update-act-rules', "SettingsControllerV2@updateActRules");


//****** Area List ****//
    Route::get('settings/get-division-name', "SettingsControllerV2@divisionName");
    Route::get('settings/get-thana-by-district-id', 'SettingsControllerV2@get_thana_by_district_id');
    Route::get('settings/get-district', 'SettingsControllerV2@getDistrict');
    Route::get('settings/area-list', "SettingsControllerV2@AreaList");
    Route::post('settings/store-area', "SettingsControllerV2@StoreArea");
    Route::get('settings/edit-area/{id}', "SettingsControllerV2@editArea");
    Route::patch('settings/update-area/{id}', "SettingsControllerV2@updateArea");



//****** Terms & Condition List ****//
    Route::get('settings/terms-condition', "SettingsControllerV2@TermsConditionList");
    Route::post('settings/store-terms-condition', "SettingsControllerV2@StoreTermsCondition");
    Route::get('settings/edit-terms-condition/{id}', "SettingsControllerV2@editTermsCondition");
    Route::post('settings/update-terms-condition', "SettingsControllerV2@updateTermsCondition");

//****** Company Info List ****//
    Route::get('settings/company-info', "SettingsControllerV2@CompanyInfoList");
    Route::get('settings/get-country-name', "SettingsControllerV2@CountryName");
    Route::post('settings/store-company-info', "SettingsControllerV2@StoreCompanyInfo");
    Route::get('settings/company-info-edit/{id}', 'SettingsControllerV2@companyInfoAction');
    Route::get('settings/approved-change-status/{company_id}','SettingsControllerV2@companyApprovedStatus');
    Route::get('settings/rejected-change-status/{company_id}','SettingsControllerV2@companyRejectedStatus');
    Route::get('settings/company-change-status/{id}/{status_id}','SettingsControllerV2@companyChangeStatus');

    //****** Rejected Draft Company ****//
    Route::get('settings/rejected-draft-company-list', 'SettingsControllerV2@rejectedDraftCompanyList');
    Route::get('settings/rejected-draft-company-change-status/{id}', 'SettingsControllerV2@rejectedDraftCompanyReject');


//****** user type List ****//
    Route::get('settings/user-type-list', "SettingsControllerV2@userTypeList");
    Route::get('settings/edit-user-type/{id}', "SettingsControllerV2@editUserType");
    Route::get('settings/get-security-list', "SettingsControllerV2@getSecurityList");
    Route::patch('settings/update-user-type/{id}', "SettingsControllerV2@updateUserType");



    //****** user-manual List ****//
    Route::get('settings/user-manual', "SettingsControllerV2@UserManualList");
    Route::post('settings/home-page/store-user-manual', "SettingsControllerV2@UsermanualStore");
    Route::get('settings/home-page/edit-user-manual/{id}', "SettingsControllerV2@editUsermanual");
    Route::get('settings/get-process-type', "SettingsControllerV2@getProcessType");
    Route::post('settings/home-page/update-user-manual', "SettingsControllerV2@updateUsermanual");

    //****** whats-new List ****//
    Route::get('settings/whats-new', "SettingsControllerV2@WhatsNewList");
    Route::post('settings/home-page/store-whats-new', "SettingsControllerV2@whatsNewStore");
    Route::get('settings/home-page/edit-whats-new/{id}', "SettingsControllerV2@editWhatsNew");
    Route::post('settings/home-page/update-whats-new', "SettingsControllerV2@updateWhatsNew");

    //****** home-page-content List ****//
    Route::get('settings/home-page/home-page-content', "SettingsControllerV2@homeContentList");
    Route::post('settings/home-page/store-home-page-content', "SettingsControllerV2@homeContentStore");
    Route::get('settings/home-page/edit-home-page-content/{id}', "SettingsControllerV2@edithomeContent");
    Route::post('settings/home-page/update-home-page-content', "SettingsControllerV2@updatehomeContent");

    //****** Industrial city List ****//
    Route::get('settings/home-page/industrial-city', "IndustrialCityController@industrialCityList");
    Route::post('settings/home-page/store-industrial-city', "IndustrialCityController@industrialCityStore");
    Route::get('settings/home-page/edit-industrial-city/{id}', "IndustrialCityController@editIndustrialCity");
    Route::post('settings/home-page/update-industrial-city', "IndustrialCityController@updateIndustrialCity");
    Route::get('settings/get-upazila-name', "IndustrialCityController@upazilaName");
    Route::get('/settings/get-upazila-by-district-id', 'IndustrialCityController@getUpazila');
    Route::get('/settings/home-page/get-homeOffice', 'IndustrialCityController@getHomeoffice');

    Route::get('settings/home-page/industrial-city/master-plan-list/{city_id}', 'MasterPlanController@masterPlanList');
    Route::post('settings/home-page/industrial-city/master-plan/create', 'MasterPlanController@createMasterPlan');
    Route::get('settings/home-page/industrial-city/master-plan/edit/{city_id}', 'MasterPlanController@editMasterPlanDetails');
    Route::post('settings/home-page/industrial-city/master-plan/update', 'MasterPlanController@updateMasterPlanDetails');

    //****** home-page-articles ****//
    Route::get('settings/home-page/home-page-articles', "SettingsControllerV2@homeArticlesList");
    Route::post('settings/home-page/store-home-page-articles', "SettingsControllerV2@homeArticlesStore");
    Route::get('settings/home-page/edit-home-page-articles/{id}', "SettingsControllerV2@edithomeArticles");
    Route::post('settings/home-page/update-home-page-articles', "SettingsControllerV2@updatehomeArticles");

    //****** industrial advisor ****//
    Route::get('settings/home-page/industrial-advisor', "IndustrialAdvisorController@IndustrialAdvisorList");
    Route::post('settings/home-page/store-industrial-advisor', "IndustrialAdvisorController@IndustrialAdvisorStore");
    Route::get('settings/home-page/edit-industrial-advisor/{id}', "IndustrialAdvisorController@editIndustrialAdvisor");
    Route::post('settings/home-page/update-industrial-advisor', "IndustrialAdvisorController@updateIndustrialAdvisor");

//****** home-page-slider List ****//
    Route::get('settings/home-page/home-page-slider-list', "SettingsControllerV2@HomePageSliderList");
    Route::post('settings/home-page/store-home-page-slider', "SettingsControllerV2@homePageSliderStore");
    Route::get('settings/home-page/edit-home-page-slider/{id}', "SettingsControllerV2@editHomePageSlider");
    Route::post('settings/home-page/update-home-page-slider', "SettingsControllerV2@updateHomePageSlider");


//****** PdfPrintRequests List ****//
    Route::get('settings/pdf-print-requests', "SettingsControllerV2@pdfPrintRequest");
    Route::get('settings/resend-pdf-print-requests/{id}', "SettingsControllerV2@resendPdfPrintRequest");
    Route::get('settings/edit-pdf-print-requests/{id}', "SettingsControllerV2@editPdfPrintRequest");
    Route::patch('/settings/update-pdf-print-requests/{id}', "SettingsControllerV2@updatePdfPrintRequest");
    Route::get('settings/pdf-print-request-verify/{pdf_id}/{certificate_name}', "SettingsControllerV2@verifyPdfPrintRequest");

    //****** Email-SMS-Query List ****//
    Route::get('settings/email-sms-queue', 'SettingsControllerV2@emailSmsQueueList');
    Route::get('settings/email-sms-queue-edit/{id}', 'SettingsControllerV2@editEmailSmsQueue');
    Route::patch('settings/update-email-sms-queue/{id}', "SettingsControllerV2@updateEmailSmsQueue");
    Route::get('settings/resend-email-sms-queue/{id}/{type}', 'SettingsControllerV2@resendEmailSmsQueue');

    //****** Security List ****//
    Route::get('settings/security', "SettingsControllerV2@SecurityList");
    Route::post('settings/store-security', "SettingsControllerV2@storeSecurity");
    Route::get('settings/edit-security/{id}', "SettingsControllerV2@editSecurity");
    Route::post('settings/update-security/{id}', "SettingsControllerV2@updateSecurity");

    //****** Regulatory Agency List ****//
    Route::get('settings/home-page/regulatory-agency', "RegulatoryAgencyController@RegulatoryAgencyList");
    Route::post('settings/home-page/store-regulatory-agency', "RegulatoryAgencyController@storeRegulatoryAgency");
    Route::get('settings/home-page/edit-regulatory-agency/{id}', "RegulatoryAgencyController@editRegulatoryAgency");
    Route::post('settings/home-page/update-regulatory-agency', "RegulatoryAgencyController@updateRegulatoryAgency");

    //****** Regulatory Agency List ****//
    Route::get('settings/home-page/regulatory-agency-details', "RegulatoryAgencyController@RegulatoryAgencyDetailsList");
    Route::get('settings/home-page/regulatory-agency-details/regulatory-agency', "RegulatoryAgencyController@RegulatoryAgencyName");
    Route::post('settings/home-page/store-regulatory-agency-details', "RegulatoryAgencyController@storeRegulatoryAgencyDetails");
    Route::get('settings/home-page/edit-regulatory-agency-details/{id}', "RegulatoryAgencyController@editRegulatoryAgencyDetails");
    Route::post('settings/home-page/update-regulatory-agency-details', "RegulatoryAgencyController@updateRegulatoryAgencyDetails");

//    Route::post('settings/upload-file-data', "SettingsController@uploadfile");

//****** Logo List ****//
    Route::post('settings/update-logo', "SettingsControllerV2@storeLogo");
    Route::get('settings/logo-edit', "SettingsControllerV2@editLogo");

//****** service-details List ****//
    Route::get('settings/service-details', "SettingsControllerV2@ServiceDetailsList");
    Route::get('settings/service-details-create', "SettingsControllerV2@createServiceDetails");
    Route::get('settings/service-details-edit/{id}', "SettingsControllerV2@editServiceDetails");
    Route::post('settings/service-details-store', "SettingsControllerV2@storeServiceDetails");
    Route::post('settings/service-details-update', "SettingsControllerV2@updateServiceDetails");


    Route::get('settings/application-guideline', 'SettingsControllerV2@applicationGuideline');
    Route::get('settings/get-guideline', 'SettingsControllerV2@applicationGuidelineList');
    Route::get('settings/application-guideline/create', 'SettingsControllerV2@applicationGuidelineCreate');
    Route::post('settings/application_guideline/store', 'SettingsControllerV2@applicationGuidelineStore');
    Route::get('settings/application-guideline/edit/{id}', 'SettingsControllerV2@applicationGuidelineEdit');
    Route::post('settings/application-guideline/update', 'SettingsControllerV2@applicationGuidelineUpdate');

    //****** Special service List ****//
    Route::get('settings/special-service', 'SettingsControllerV2@specialService');
    Route::get('settings/get-service', 'SettingsControllerV2@specialServiceList');
    Route::get('settings/get-service/create', 'SettingsControllerV2@specialServiceCreate');
    Route::post('settings/get-service/store', 'SettingsControllerV2@specialServiceStore');
    Route::get('settings/get-service/status_update/{id}', 'SettingsControllerV2@specialServiceStatusUpdate');
    Route::get('settings/get-service/form/{id}', 'SettingsControllerV2@specialServiceForm');
    Route::get('settings/get-service/edit/{id}', 'SettingsControllerV2@specialServiceEdit');
    Route::post('settings/get-service/update', 'SettingsControllerV2@specialServiceUpdate');

    // ************ D-Nothi User Mapping ***********
    Route::get('settings/d-nothi-user-mapp', 'SettingsControllerV2@dNothiUserMapp');

//****** Soft Delete ****//
    Route::get('settings/delete/{model}/{id}', "SettingsControllerV2@softDelete");


    //****** Maintenance Mode  ****//
//    Route::get('settings/maintenance-mode-list', "SettingsControllerV2@maintenanceModeget");
//    Route::get('settings/maintenance-mode/remove-user/{user_id}', "SettingsControllerV2@removeUserFromMaintenance");
//    Route::post('settings/maintenance-mode/store', "SettingsControllerV2@maintenanceModeStore");




    Route::get('settings/document-v2', 'DocumentSettingsController@index');
    Route::get('settings/document-v2/document-list', 'DocumentSettingsController@getDocumentList');
    Route::get('settings/document-v2/create', 'DocumentSettingsController@createDocument');
    Route::get('settings/document-v2/edit/{document_id}', 'DocumentSettingsController@editDocument');
    Route::post('settings/document-v2/store', 'DocumentSettingsController@storeDocument');
    Route::post('settings/document-v2/update/{document_id}', 'DocumentSettingsController@storeDocument');
    Route::post('settings/document-v2/get-document-type', 'DocumentSettingsController@getDocumentType');


    Route::get('settings/document-v2/service-document-list', 'DocumentSettingsController@getServiceDocumentList');
    Route::get('settings/document-v2/service/create', 'DocumentSettingsController@createServiceDocument');
    Route::get('settings/document-v2/service/edit/{service_doc_id}', 'DocumentSettingsController@editServiceDocument');
    Route::post('settings/document-v2/service/store', 'DocumentSettingsController@storeServiceDocument');
    Route::post('settings/document-v2/service/update/{service_doc_id}', 'DocumentSettingsController@storeServiceDocument');


// Route to handle page reload in Vue except for api routes
    Route::get('settings/{index?}', 'SettingsControllerV2@index')->where('index', '(.*)');
//    Route::get('settings/{index?}', 'SettingsControllerV2@branch')->where('index', '(.*)');


});

